<?php
namespace Main\Controller;
use think\Controller;
use think\Db;
/**
 *  功能处理类
 */
class FuncController extends Controller
{
	// 数据库所需要的字段
	private $dbName =  '';

	public function _initialize(){
		// $this->dbName = date("Ym");
		$this->dbName = "infor";
	}

	// 检查数据表是否存在
	public function checkDatabases(){
		// 获取当前日期
		$isTable  = M()->query("SHOW TABLES LIKE '{$this->dbName}'");
		if($isTable) return true; // 表存在
		else $this->createDatabses();
	}

	// 创建新的数据表
	public function createDatabses(){
		$sql = "CREATE TABLE IF NOT EXISTS `$this->dbName` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
				`classify` int(11) NOT NULL,
				`score` int(11) NOT NULL,
				`companyid` varchar(100) NOT NULL,
				-- `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				`addyear` int(11) NOT NULL,
				`addmonth` int(11) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		M()->execute($sql);
	}

	// 查找数据库中所有 时间格式的表
	public function queryDatabases(){
		$result = M()->db()->getTables();
		foreach ($result as $key => $value) {
			if($value == "user" || $value == "company"){
				unset($result[$key]);
			}
		}
		return $result;
	}

}