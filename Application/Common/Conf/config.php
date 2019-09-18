<?php
return array(
	//'配置项'=>'配置值'
    'URL_CASE_INSENSITIVE' => true, // 配置URL不区分大小写

    // 允许访问的模块
     'MODULE_ALLOW_LIST' => array("Home","Main"),

    "__PUBLIC__"  => __ROOT__.'/Public',

    // 默认模块绑定
    'DEFAULT_MODULE' => 'Main',

    // 加载数据库配置
    'LOAD_EXT_CONFIG' => 'db',

    // layout相关配置
    'LAYOUT_ON' => true,
    'LAYOUT_NAME' => 'infor',

);