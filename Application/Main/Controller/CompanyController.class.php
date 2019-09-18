<?php
namespace Main\Controller;
use think\Controller;

class CompanyController extends Controller
{
	/**
	 *  页面
	 */
	public function main(){
		$topData = M('company')->where(['pid'=>0])->select();
		$this->assign("root",$topData);
		$this->display('index');
	}
	/**
	 *  添加上报单位
	 */
	public function add()
	{
		if(IS_POST){
            $companyModel = D('Company');
            if(!$companyModel->create($_POST)){  // 后台数据验证
            	$this->ajaxReturn(onError($companyModel->getError()));
            }else{
            	$result = $companyModel->addData($_POST);
				if($result) $this->ajaxReturn(onOk("添加成功"));
				else $this->ajaxReturn(onError("添加失败"));
            }
		}else{
			$this->ajaxReturn(onError("网络请求出错,请刷新页面重试"));
		}
	}
	/**
	 *  编辑上报单位
	 */
	public function edit()
	{
		$compantObj = M('infor');
		if(IS_POST){
			$fatherID = $compantObj->where(['id'=>I('post.pid')])->field('id')->find();
			$data = I('post.');
			$data['path'] = $fatherID['id'].'-'.I('post.id');
			$result = $compantObj->save($data);
			if($result) $this->ajaxReturn(onOk("更新成功"));
			else $this->ajaxReturn(onError("更新失败"));
		}else{
			$id = I('get.id');
			$companyData = $compantObj->where(['id'=>$id])->find();
			$topData = M('infor')->where(['pid'=>0])->select();
			$this->assign("root",$topData);
			$this->assign('companyData',$companyData);
			$this->display('edit');
		}
	}
	/**
	 * 删除上报单位
	 */
	public function delete()
	{
		if(IS_POST){
			$deleteID = I('post.id');
			$compantObj = M('Company');
			$queryData = $compantObj->where(['pid'=>$deleteID])->select();
			if(count($queryData) == 0 && empty($queryData)){
				$result = $compantObj->delete($deleteID);
				if($result) $this->ajaxReturn(onOk("添加成功"));
				else $this->ajaxReturn(onError("添加失败"));
			}else{
				$this->ajaxReturn(onError("删除失败。该上报单位下还有子单位,请先删除子单位"));
			}
		}else{
			$this->ajaxReturn(onError("网络请求出错,请刷新页面重试"));
		}
	}
	/**
	 *  展示所有的单位
	 */
	public function getCompany(){
		$companyData = M("company")->field('pid', true)->order('path asc')->select();
		foreach ($companyData as $k => $v) {
		    $companyData[$k]['name'] = str_repeat('└─ ', $v['level']) . $companyData[$k]['name'];
		    unset($companyData[$k]['path']);
		    unset($companyData[$k]['level']);
		}
		$this->ajaxReturn(onOk($companyData));
	}
}