<?php
namespace Main\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	// 检测有没有登录的信息
    	if(!session('username'))	A('Login')->index();
    	else $this->display("./index");
    }
    public function welcome(){
    	echo "<h3>欢迎使用</h3><br/>";
    }
}