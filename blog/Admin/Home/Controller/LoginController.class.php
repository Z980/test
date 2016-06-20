<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller{
	public function login(){
		if(!empty($_POST)){
			$admin=M('admin')->where(array('administrator'=>encryption($_POST['username'])))->find();
			if($admin){
			if(M('admin')->where(array('password'=>encryption($_POST['password'])))->find()){
				session('administrator',$admin);
				$this->redirect('Index/index');
			}else{
				$this->error('密码错误');
			}
		}else{
			$this->error('页面不存在');
		}
	}
		$this->display();
	}
}