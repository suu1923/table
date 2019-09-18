<?php
namespace Main\Controller;

use think\Controller;

class LoginController extends Controller
{

	public function index()
	{
		$this->display('Login/index');
	}

	/**
	 * 登录
	 */
	public function login()
	{
		if (IS_POST) {
		    $userObj = M('company');
		    $username = $userObj->where(["username"=>I('post.username')])->find();
		    if (!$username) {
				$this->ajaxReturn(onError("用户名不存在,请联系超级管理员"));
		    }
		    if (md5(I('post.password')) != $username['password']) {
				$this->ajaxReturn(onError("密码错误"));
		    }
		    // 存储session
            session('uid',$username['id']);
		    session('username', $username['username']);   // 当前用户名
		    session('nickname', $username['nickname']);   // 当前用户昵称
		    // 记录时间IP
		    $time = date("Y-m-d H:i:s");
		    $ip = get_client_ip();

		    @$userObj->where(["id"=>$username['id']])->save(["lasttime"=>$time,"lastip"=>$ip]);

			$this->ajaxReturn(onOk("登陆成功"));
		} else {
			$this->ajaxReturn(onError("网络请求出错,请刷新页面重试"));
		}
	}
	/**
	 * 登出
	 */
	public function logout()
	{
		session(null);
		// 防止输出中文乱码
		redirect(U('Index/index'), 2, '正在退出后台...');
	}

}