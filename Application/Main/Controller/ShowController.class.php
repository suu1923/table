<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/17
 * Time: 12:17
 */

namespace Main\Controller;


use Think\Controller;

class ShowController extends Controller
{

    public function getQrcode($id){
        if(empty($id)){
            return "获取ID出错";
        }

        $data = D("Tables")->where(['id'=>$id,'status'=>0])->field("company_id,create_time,update_time,status,pwd,file_name,id",true)->find();
//        dump($data);
        $this->assign("qrdata",$data);

        if ($data['type'] == 1){
            $this->display("show_table");
        }else{
            $this->display("show_pic");
        }


    }
}