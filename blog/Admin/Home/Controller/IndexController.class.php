<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    	public function index(){
		if(!empty($_SESSION['administrator']) && M('admin')->where(array('administrator'=>$_SESSION['administrator']))->find()){
                $userinfo=new \Admin\Model\IndexModel();
                $count=$userinfo->count();
                $page=new \Think\Page($count,15);
                $show=$page->show();
                $list=$userinfo->order('regittime')->limit($page->firstRow.','.$page->listRows)->select();
               
		}else{
		
		}
		$this->display();
	}
}