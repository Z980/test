<?php
return array(
	'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'weibo',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'weibo_',    // 数据库表前缀
    'ENCRYPTION_KEY'        =>  'lsbm931217', //异位或加密key
    'COOKIE_TIME'           =>  time()+3600*24*7,//cookie保存时间一周
    'default_face'          =>  './Uploads/userphoto/face/default.jpeg',
    'DEFAULT_MODULE'        =>   'Home',  // 默认模块
    'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'index', // 默认操作名称//默认头像
    
);