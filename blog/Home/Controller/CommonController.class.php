<?php

namespace Home\Controller;
use Think\Controller;

class CommonController extends Controller {
    public function _initialize() {
        if (isset($_COOKIE['username']) && !isset($_SESSION['uid'])) {
            $value = $_COOKIE['username'];
            $value = explode('+', encryption($_COOKIE['username'], 1));
            $ip = get_client_ip();
            //本次登录IP与上一次登录IP一致时
            if ($ip == $value[1]) {
                $user = $value[0];
                $where = array('username' => $user);
                $user = M('user')->where($where)->field(array('id', 'username', 'lock'))->find();
                //检索出用户信息并且该用户没有被锁定时，保存登录状态
                if ($user && !$user['lock']) {
                    $where = array('uid' => $user['id']);
                    $nikename = M('userinfo')->where($where)->field('nikename')->find();
                    if ($nikename) {
                        session('username', $nikename['nikename']);
                    } else {
                        session('username', $user['username']);
                    }
                    session('uid', $user['id']);
                }
            }
        }
        if(isset($_SESSION['uid'])){
            $info=M('userinfo')->where(array('uid'=>$_SESSION['uid']))->find();
            $username=M('user')->where(array('id'=>$_SESSION['uid']))->getField('username');
            if($info){
               if(empty($info['nikename'])){
                session('username',$username);
               }else{
                session('username',$info['nikename']);
               }
               if(empty($info['thumb'])){
                $info['thumb']=C('default_face');
               }
               session('thumb',__ROOT__.$info['thumb']);
            }else{
                session('username',$username);
            }
            $count=M('weibo')->where(array('uid'=>$_SESSION['uid']))->count();
            session('saycount',$count);
        }
    }

}
