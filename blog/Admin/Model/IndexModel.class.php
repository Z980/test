<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class IndexModel extends ViewModel{
	//视图模型
	public $viewFields=array(
		'user'=>array('id','username','password','registime','lock','_type'=>'LEFT'),
        'userinfo'=>array('nikename','sex','intro','location','thumb','_on'=>'user.id=userinfo.uid','_type'=>'LEFT')
		);
}