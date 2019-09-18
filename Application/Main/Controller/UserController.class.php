<?php
namespace Main\Controller;
use think\Controller;

class UserController extends Controller
{

	public function main(){
		$this->display("index");
	}

	public function add(){
		if(IS_POST){
			$userObj = M('company');
			$data = I("post.");
			$checkUserName = $userObj->where(['username'=>I("post.username")])->find();
			if($checkUserName){
				$this->ajaxReturn(onError("用户名已存在,请更换"));
			}			
			$data['password'] = md5($data['password']);
			$result = $userObj->add($data);
			if($result) $this->ajaxReturn(onOk("添加成功"));
			else $this->ajaxReturn(onError("添加失败"));
		}else{
			$this->ajaxReturn(onError("网络请求出错,请刷新页面重试"));
		}
	}

	public function delete(){
		if(IS_POST){
			$deleteID = I('post.id');
			$UserObj = M('user');
			$queryData = $UserObj->where(['id'=>$deleteID])->find();
			if($queryData['username'] != "Administrator"){
				$result = $UserObj->delete($deleteID);
				if($result) $this->ajaxReturn(onOk("添加成功"));
				else $this->ajaxReturn(onError("添加失败"));
			}else{
				$this->ajaxReturn(onError("超级管理员不允许删除"));
			}
		}else{
			$this->ajaxReturn(onError("网络请求出错,请刷新页面重试"));
		}
	}

	public function edit(){
		if(IS_POST){
			$userObj = M('user');
			$data = I('post.');
			$data['password'] = md5($data['password']);
			$result = $userObj->save($data);
			if($result) $this->ajaxReturn(onOk("更改成功"));
			else $this->ajaxReturn(onError("更改失败"));
		}else{
 			$userObj = M('user');
			$userData = $userObj->where(['username'=>I("get.username")])->field("lasttime,lastip,password",true)->find();
			$this->assign("userData",$userData);
			$this->display("edit");
		}
	}

	public function getUser(){
		$userObj = M("user");
		$userData = $userObj->field("password",true)->select();
		$this->ajaxReturn(onOk($userData));
	}
}