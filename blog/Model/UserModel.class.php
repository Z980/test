<?php
namespace Model;
use Think\Model;
class UserModel extends Model{
	// 是否批处理验证
protected $patchValidate    =   true;
protected $_validate        =   array(
        array('username','2,10','用户名格式错误',0,'length'),//判断用户名字符数
        array('password','require','密码必须填写'),
    	array('confirm_password','password','确认密码不正确',0,'confirm'),/*验证确认密码是否和密码一致*/
);  // 自动验证定义
}