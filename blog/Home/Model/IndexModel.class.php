<?php
namespace Home\Model;
use Think\Model\ViewModel;
class IndexModel extends ViewModel{
	//视图模型
	public $viewFields=array(
        'weibo'=>array('id','content','time','uid','_type'=>'LEFT'),
        'user'=>array('username','_on'=>'weibo.uid=user.id','_type'=>'LEFT'),
        'userinfo'=>array('nikename','thumb','_on'=>'weibo.uid=userinfo.uid','_type'=>'LEFT'),
        'picture'=>array('pictures','_on'=>'weibo.id=picture.wid','_type'=>'LEFT'),
		);
}