<?php
namespace Main\Controller;
use Think\Controller;

class inforController extends Controller
{
	// 首页
	public function main(){
		$this->display("index");
	}
	// 添加
	public function add(){
		if(!IS_POST){
			$this->ajaxReturn(onError("网络请求出错,请刷新页面重试"));
		}else{
			$result = D("Infor")->addData(I("post."));
			if($result == true) $this->ajaxReturn(onOk("添加成功"));
			else $this->ajaxReturn(onError("添加失败"));
		}
	}
	// 编辑
	public function edit(){
		$inforObj = M('infor');
		if(IS_POST){
			$data = I('post.');
			$data['classify'] = implode(",", $data['classify']);
			$data['companyid'] = implode(",", $data['companyid']);
			$result = $inforObj->save($data);
			// dump($data['id']);
			// 删除后更新
			$inforBatObj = M("inforbat");
			$delInforBat = $inforBatObj->where(['inforid'=>$data['id']])->delete();
			$result2 = D("infor")->editDataToInforOther(I('post.'));

			if($result && $result2) $this->ajaxReturn(onOk("更新成功"));
			else $this->ajaxReturn(onError("更新失败"));
		}else{
			$id = I('get.id');
			$inforData = $inforObj->where(['id'=>$id])->find();
			$classifyArr = C("CLASSIFY_COMPANY");
			$this->assign('classifyArr',$classifyArr);
			$this->assign('inforData',$inforData);
			$this->display('edit');
		}
	}
	// 删除
	public function delete(){
		if(IS_POST){
			$deleteID = I('post.id');
			$inforObj = M('Infor');
			$inforBatObj = M('inforbat');
			$result = $inforObj->delete($deleteID);
			$result2 = $inforBatObj->where(["inforid"=>$deleteID])->delete();
			if($result && $result2) $this->ajaxReturn(onOk("删除成功"));
			else $this->ajaxReturn(onError("删除失败"));
		}else{
			$this->ajaxReturn(onError("网络请求出错,请刷新页面重试"));
		}
	}

	// 数据统计 
	public function countMain(){
		// 查询所有的上报公司的数据
		$allCompany = $this->getCompanyByTree();
		// 获取所有的年份
		$allYear = $this->getTimer("addyear");
		// 获取所有的月份
		$this->assign('companyData',$allCompany);
		$this->assign('yearData',$allYear);
		$this->display("count");
	}

	// 返回查询数据
	public function queryData($download=false){
		$company = I("get.company");
		$year = I("get.year");
		$month = I("get.month");

		$resultArr = [];
		// 获取所有的年份
		$allYear = [];
		// 获取所有的上报公司的ID
		$allCompanyId = [];
		$queryObj = M("inforbat");
		$queryData = $queryObj->field("year,companyid")->select();
		foreach ($queryData as $key => $value) {
			array_push($allYear,$queryData[$key]['year']);
			array_push($allCompanyId,$queryData[$key]['companyid']);
		}
		$allYear = array_values(array_unique($allYear));
		$allCompanyId = array_values(array_unique($allCompanyId));
		$finishArr = [];
		// 全为默认条件 全局搜索
		if($company == "#" && $year== "#" && $month == "#" ){
			// 条件全为所有
			for($cid=0;$cid<count($allCompanyId);$cid++){
				for($yid=0;$yid<count($allYear);$yid++){
					for($mid=1;$mid<=12;$mid++){
						// echo "正在获取{$allYear[$yid]}年{$mid}月{$allCompanyId[$cid]}的信息<br/>";
						array_push($finishArr, $this->foreachQuery($allCompanyId[$cid],$allYear[$yid],$mid));
					}
				}
			}
			$xlsOneName = '全部上报信息统计';

		}else if($company != "#" && $year == "#" && $month== "#"){
			// 查询所有年/月份的单位ID
			for($yid=0;$yid<count($allYear);$yid++){
				for($mid=1;$mid<=12;$mid++){
					// echo "正在获取{$allYear[$yid]}年{$mid}月{$company}的信息<br/>";
					array_push($finishArr, $this->foreachQuery($company,$allYear[$yid],$mid));
				}
			}
			$xlsOneName = $company.'全部的上报信息';
		}else if($company != "#" && $year != "#" && $month == "#"){
			// 查询某单位ID在某年
			for($mid=1;$mid<=12;$mid++){
				// echo "正在获取{$year}年{$mid}月{$company}的信息<br/>";
				array_push($finishArr, $this->foreachQuery($company,$year,$mid));
			}
			$xlsOneName = $company.$year."年上报信息";

		}else if($company != "#" && $year != "#" && $month != "#"){
			// 规定所有条件
			// echo "正在获取{$year}年{$month}月{$company}的信息<br/>";
			array_push($finishArr, $this->foreachQuery($company,$year,$month));
			
			$xlsOneName = $company.$year."年".$month."月上报信息";
		}
		// 数组去重
		$resultArr = array_values(array_filter($finishArr));
		// dump($resultArr);
		if($download == true){
        	// $this->exportExcel($xlsName,$xlsCell,$xlsData,'新闻列表');
			$xlsCell  = array(
			    array('company','上报单位名称'),
			    array('classify_0','报省'),
			    array('classify_1','省办采用'),
			    array('classify_2','今日信息'),
			    array('classify_3','下发'),
			    array('classify_4','紧急信息'),
			    array('classify_5','领导指示'),
			    array('score','分数'),
			    array('scoresum','总分数'),
			);
			$this->exportExcel($xlsOneName,$xlsOneName,$xlsCell,$resultArr,$xlsOneName);
		}else{
			$this->ajaxReturn(onOk($resultArr));
		}
	}

	// 导出Excel 
	public function exportExcel($fileName,$expTitle,$expCellName,$expTableData,$title=''){
	    vendor("PHPExcel.PHPExcel");
	    $objPHPExcel = new \PHPExcel();
	    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

	    $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
	    $fileName = $fileName;   //excel文件名称可自己定义
	    $cellNum = count($expCellName);
	    $dataNum = count($expTableData);
	    $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

	    $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
	    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $title.'  创建时间:'.date('Y-m-d H:i:s'));  //可以设置 第一行 标题内容

	    for($i=0;$i<$cellNum;$i++){
	        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
	    }
	    for($i=0;$i<$dataNum;$i++){
	        for($j=0;$j<$cellNum;$j++){
	            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
	        }
	    }

	    header('pragma:public');
	    header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
	    header("Content-Disposition:attachment;filename = $fileName.xls");  //attachment新窗口打印inline本窗口打印
	    $objWriter->save('php://output');  //文件通过浏览器下载
	    // exit;
	}

	// 查询一个单位某年那个月的
	public function foreachQuery($company,$year,$month){
		$query['companyid'] = $company;
		$query['year'] = $year;
		$query['month'] = $month;

		$queryObj = M("inforbat");
		$queryData = $queryObj -> where($query)->field('inforid',true)->select();
		
		if(!$queryData) return NULL;
		// 分类ID数组
		$values = [];
		// 分数数组
		$scores = [];
		foreach ($queryData as $queryKey => $queryValue) {
			array_push($values, $queryData[$queryKey]['classify']);
			array_push($scores,$queryData[$queryKey]['score']);
		}
		// dump($values);
		// dump(array_sum($scores));
		$countValues = array_count_values($values);
		for($i=0;$i<6;$i++){
			if($countValues[$i] == NULL)	$countValues[$i] = 0;
		}
		ksort($countValues);
		$resultArr['company'] = $this->getCompanyNameById($query['companyid']);// 查表
		foreach ($countValues as $key => $value) {
			$resultArr["classify_".$key] = $value;
		}
		$resultArr['score'] = array_sum($scores);
		$resultArr['year'] = $year;
		$resultArr['month'] = $month;
		$resultArr['scoresum'] = $this->companyScore($query['companyid']);
		return $resultArr;
	}


	// -------------------------------------------方法们
	public function getTimer($timer){
		$data = M("infor")->field($timer)->select();
		$result = [];
		foreach ($data as $key => $value) {
			array_push($result, $data[$key]['addyear']);
		}
		$result = array_unique($result);
		return $result;
	}
	// 获取单位一共得过多分
	public function companyScore($id){
		$companyScore = M("inforbat")->where(['companyid'=>$id])->field('score')->select();
		$scoreArr = [];
		foreach ($companyScore as $key) {
			array_push($scoreArr, $key['score']);
		}
		return array_sum($scoreArr);
	}
	// 根绝单位ID获取单位名称
	public function getCompanyNameById($id){
		$result = M('company')->where(['id'=>$id])->field("name")->find();
		return $result['name'];
	}
	// company 树形结构
	public function getCompanyByTree(){
		$companyData = M("company")->order('path asc')->select();
		foreach ($companyData as $k => $v) {
		    $companyData[$k]['name'] = str_repeat('└─ ', $v['level']) . $companyData[$k]['name'];
		    unset($companyData[$k]['path']);
		    unset($companyData[$k]['level']);
		}
		return $companyData;
	}
	// 获取信息JSON结构
	public function getInfoJson(){
		$inforData = M('infor')->order('addtime desc')->select();
		foreach ($inforData as $k => $v) {
			$inforData[$k]["classify"] = $this->analysisClassify($inforData[$k]["classify"]);
			$inforData[$k]["companyid"] = $this->analysisCompanyid($inforData[$k]["companyid"]);
		    unset($inforData[$k]['addyear']);
		    unset($inforData[$k]['addtime']);
		}
		$this->ajaxReturn(onOk($inforData));
	}
	// 转化 信息分类
	public function analysisClassify($str){
		$arr = explode(",", $str);
		foreach ($arr as $akey => $avalue) {
			// 查询C方法中的
			$arr[$akey] = C('CLASSIFY_COMPANY.'.$avalue); 
		}
		$result = implode(",", $arr);
		return  $result;
	}
	// 转化 上报公司
	public function analysisCompanyid($str){
		$arr = explode(",", $str);
		$companyObj = M("company");
		foreach ($arr as $key => $value) {
			$trans = $companyObj->where(["id"=>$value])->field("name")->find();
			$arr[$key] = $trans['name'];
		}
		$result = implode(",", $arr);
		return  $result;
	}
	// 获取上报公司 -- JSON 
	public function companyJson($condition = ""){
		if(!empty($condition)){
			echo json_encode($this->getCompanyCheck($condition));
		}else{
			echo json_encode($this->getCompany());
		}
	}
	// 递归获取对应的结构
	public function getCompany($pid = 0, &$result = array()){
		$companyData = M('company')->where(['pid'=>$pid])->select();
        if (!$companyData) return [];
		foreach ($companyData as $key => $value) {
			// 重新赋值
			$companyData[$key]["title"] = $companyData[$key]['name'];
			$companyData[$key]["value"] = $companyData[$key]['id'];
			// 删掉之前的
			unset($companyData[$key]["id"]);
			unset($companyData[$key]["name"]);
			unset($companyData[$key]["level"]);
			unset($companyData[$key]["path"]);
		}
		foreach ($companyData as $arr) {
			$thisArr = &$result[];
			$arr['data'] = $this->getCompany($arr['value'],$thisArr);
			$thisArr = $arr;
		}
		return $result;
	}
	public function getCompanyCheck($checked="",$pid = 0, &$result = array()){
		$checkedArr = explode(",", $checked);
		$companyData = M('company')->where(['pid'=>$pid])->select();
        if (!$companyData) return [];
		foreach ($companyData as $key => $value) {
			for ($i=0; $i < count($checkedArr); $i++) { 
				// echo "当前循环第{$i}个 值为{$checkedArr[$i]} 获取到的上级循环的ID为 {$companyData[$key]['id']} <br/>";
				if($companyData[$key]['id'] == $checkedArr[$i]){
					$companyData[$key]["checked"] = true;
				}
			}
			// 重新赋值
			$companyData[$key]["title"] = $companyData[$key]['name'];
			$companyData[$key]["value"] = $companyData[$key]['id'];
			// 删掉之前的
			unset($companyData[$key]["id"]);
			unset($companyData[$key]["name"]);
			unset($companyData[$key]["level"]);
			unset($companyData[$key]["path"]);
		}
		foreach ($companyData as $arr) {
			$thisArr = &$result[];
			$arr['data'] = $this->getCompanyCheck($checked,$arr['value'],$thisArr);
			$thisArr = $arr;
		}
		return $result;
	}
}