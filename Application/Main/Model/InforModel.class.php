<?php 
namespace Main\Model;

use Think\Model;

/**
 *  信息模型
 */
class InforModel extends Model
{
	public function addData($data){
		$addData['title'] = $data['title'];
		$addData['score'] = $data['score'];
		$addData['classify'] = implode(",", $data['classify']);
		$addData['companyid'] = implode(",", $data['companyid']);
		$addData['addyear'] = date("Y");
		$addData['addmonth'] = date("m");
		$addData['addtime'] = date("Y-m-d H:i:s");
		$newID = $this->add($addData);
		// dump($newID);
		$result = $this->addDataToInforOther($newID,$data);
		if($result){
			return true;
		}else{
			return false;
		}
	}

	public function addDataToInforOther($inforid,$data){
		// 遍历companyid
		$companyCount =  count($data['companyid']);
		$classifyCount = count($data['classify']);
		if(checkArrOnly($data['companyid']) > 1){
			$data['score'] /= 2;
		}else{
			$data['score'] = $data['score'];
		}
		$addData['inforid'] = $inforid;
		$addData['year'] = date("Y");
		$addData['month'] = date("m");
		$addData['score'] = $data['score'];
		// 循环上报单位
		for($i=0;$i<$companyCount;$i++){
				$addData['companyid'] = $data['companyid'][$i];
			// 循环分类
			for($j=0;$j<$classifyCount;$j++){
				$addData['classify'] = $data['classify'][$j];
				$result = M('inforbat')->add($addData);
			}
		}
		if($result) return true;
		else return false;
	}

	public function editDataToInforOther($data){
		// 相对于inforbat表来说
		$companyCount =  count($data['companyid']);
		$classifyCount = count($data['classify']);
		if(checkArrOnly($data['companyid']) > 1){
			$data['score'] /= 2;
		}else{
			$data['score'] = $data['score'];
		}
		// 获取原 年/月
		$getYM = M('infor')->where(['id'=>$data['id']])->field('addyear,addmonth')->find();
		// dump($getYM);
		$addData['inforid'] = $data['id'];
		$addData['year'] = $getYM['addyear'];
		$addData['month'] = $getYM["addmonth"];
		$addData['score'] = $data['score'];
		// 循环上报单位
		for($i=0;$i<$companyCount;$i++){
				$addData['companyid'] = $data['companyid'][$i];
			// 循环分类
			for($j=0;$j<$classifyCount;$j++){
				$addData['classify'] = $data['classify'][$j];
				$result = M('inforbat')->add($addData);
			}
		}
		if($result) return true;
		else return false;
	}
}