<?php
namespace Main\Model;
use Think\Model;
class CompanyModel extends Model
{

    // 自动验证
    protected $_validate = array(
        array('name','','单位名称已经存在',0,'unique',1), // 在新增的时候验证name字段是否唯一
    );


	public function addData($data){
		$newID = $this->add($data);
		if($data['pid'] != 0){ // 为字菜单的情况下
			$pInfo = $this->find($data['pid']);
			$pPath = $pInfo['path'];
			$path  = $pPath ."-".$newID;
		}else{
			$path = $newID;
		}
		$level = count(explode('-', $path))-1;
		$dataArr = array(
			'id' => $newID,
			'path' => $path,
			'level' => $level,
		);
		return $this->save($dataArr);
	}
}