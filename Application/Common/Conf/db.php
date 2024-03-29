<?php
/* 数据库配置文件 */
return array(
    'DB_TYPE'               =>  'mysql',                // 数据库类型
    'DB_HOST'               =>  '127.0.0.1',            // 服务器地址  120.77.153.138
    'DB_NAME'               =>  'tables',          // 数据库名
    'DB_USER'               =>  'root',                 // 用户名
    'DB_PWD'                =>  'root',             // 密码
    'DB_PORT'               =>  '3306',                 // 端口
    // 'DB_PREFIX'             =>  'think_',               // 数据库表前缀
    'DB_PARAMS'          	=>  array(),                // 数据库连接参数
    'DB_DEBUG'  			=>  TRUE,                   // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'       =>  true,                   // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',                 // 数据库编码默认采用utf8
);