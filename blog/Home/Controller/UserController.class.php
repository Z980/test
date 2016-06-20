<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends CommonController{
	public function login(){
		/*
		用户登陆
		 */
		if(!isset($_POST)){
			E('页面不存在');
		}
		$user=M('user');
		$where=array('username' =>$_POST['username']);
		//验证用户名密码
		$info=$user->where($where)->find();
		if($info){
          if($info['password']===encryption($_POST['password'])){
          	if(isset($_POST['save'])){
          		$value=$_POST['username'];
          		$ip=get_client_ip();
          		$value=$value.'+'.$ip;
          		$value=encryption($value);
          		@setcookie('username',$value,C('COOKIE_TIME'),'/');

          	}
             session('uid',$info['id']);
          	$this->redirect('Index/index');
          }else{
          	$this->error('密码错误');
          }
      }else{
         $this->error('用户不存在');
     }

 }
	/**
   *注册*
   * @return [type] [description]
   */
    public function register(){
    	if(!empty($_POST)){
    		$verify=new \Think\Verify();
    		$info=$verify->check($_POST['verify']);
    		if(!$info){
    			$this->error('验证码错误');
    		}
           $user=new \Model\UserModel();
           $_POST['registime']=date('Ymd');
           $info=$user->create();
           if(!$info){
               $this->getError('输入信息有误');

           }
           $user->password=encryption($_POST['password']);
           $info=$user->add();
           if($info){
               $this->redirect('Index/index',array(),2,"注册成功");
           }else{
               $this->error("注册失败");
           }
       }
       $this->display();
   }
   public function verify(){
    $cfg = array(
            'fontSize' => 15, // 验证码字体大小(px)
            'useCurve' => false, // 是否画混淆曲线
            'useNoise' => false, // 是否添加杂点	
            'imageH' => 40,
            'imageW' => 100,
            'length' => 4, // 验证码位数
            'fontttf' => '2.ttf', // 验证码字体，不设置随机获取
            );
        //实例化verif.class.php对象
    $very = new \Think\Verify($cfg);
    echo $very->entry();
}
public function namecheck(){
   if(!IS_AJAX){
      $this->error('页面不存在');
  }
  $username=$_POST['username'];
  $where=array('username'=>$username);
  if (M('user')->where($where)->find()) {
      echo 'false';
  }else{
      echo 'true';
  }
}
public function emailcheck(){
   if(!IS_AJAX){
      $this->error('页面不存在');
  }
  $email=$_POST['email'];
  $where=array('email'=>$email);
  if (M('user')->where($where)->find()) {
      echo 'false';
  }else{
      echo 'true';
  }
}
    /**
     * *用户信息修改页面展示
     * @return [type] [description]
     */
    public function userinfo(){
        $field=array("nikename","sex","location","intro","thumb");
        $where=array('uid' => $_SESSION['uid']);
        $info=M('userinfo')->where($where)->field($field)->find();
        $location=explode('+',$info['location']);
        $province=$location[0];
        $city=$location[1];
        $area=$location[2];
        $user=M('user')->where(array('id'=>$_SESSION['uid']))->getField('email');
        if($info){
          if(empty($info['thumb'])){
            $info['thumb']=C('default_face');
          }
          if($user){
            $userinfo=array('sex'=>$info['sex'],'province'=>$province,'city'=>$city,'area'=>$area,'intro'=>$info['intro'],'nikename'=>$info['nikename'],'thumb'=>__ROOT__.$info['thumb'],'email'=>$user);
            $this->assign('userinfo',$userinfo);}
        }else{
            $userinfo=array('sex'=>0,'thumb'=>__ROOT__.C('default_face'),'email'=>$user);
            $this->assign('userinfo',$userinfo);

        }
        session('userthumb',$userinfo['thumb']);
        $this->display();
    }
    /**
     * 修改个人资料
     * @return [type] [description]
     */
    public function editinfo(){
        if(!IS_POST){
            $this->error('页面不存在');
        }
        if(!empty($_POST)){
            $userinfo=D('userinfo');
            if($_POST['sex']==1){
               $_POST['sex']='男';
           }elseif($_POST['sex']==2){
            $_POST['sex']='女';
        }
        $array=array($_POST['location_p'],$_POST['location_c'],$_POST['location_a']);
        $_POST['location']=implode('+',$array);
        $_POST['uid']=$_SESSION['uid'];
        $where=array('uid'=>$_SESSION['uid']);
        $info=$userinfo->where($where)->find();
        if($info){
            $data=array('nikename'=>$_POST['nikename'],'sex'=>$_POST['sex'],'location'=>$_POST['location'],'intro'=>$_POST['intro']);
            $result=$userinfo->where($where)->save($data);
            if(false!==$result){
              $nikethumb=M('userinfo')->where(array('uid'=>$_SESSION['uid']))->find();
              $nikename=$nikethumb['nikename'];
              $thumb=$nikethumb['thumb'];
              session('userthumb',__ROOT__.$thumb);
              session('username',$nikename);
                $this->success('修改成功','userinfo');
            }else{
                $this->error('修改失败');
            }
        }else{
            $userinfo->create();
            $result=$userinfo->add();
            if($result){
                $this->success('修改成功','userinfo');
            }else{
                $this->error('修改失败');
            }
        }
    }

  }
  /**
   * 退出登录
   * @return [type] [description]
   */
  public function logout(){
    session(null);//清空session
    cookie('username',null);//清空cookie
    $this->redirect('Index/index');
  }
  /**
   * 用户头像上传
   * @return [type] [description]
   */
  public function facephoto(){
    $config=array(
    'maxSize'       =>  2000000, //上传的文件大小限制 (0-不做限制)
    'exts'          =>  array('jpg','jpeg','png','gif'),//允许上传的文件后缀
    'replace'       =>  true,
    'savePath'      =>  'userphoto/face/',
    'autoSub'       => false
    );
    $userphoto=new \Think\Upload($config);
    $userphoto->saveName=$_SESSION['uid'];//保存名为用户id
    $info=$userphoto->upload();
    if($info){
    $img='./Uploads/'.$info['Filedata']['savepath'].$info['Filedata']['savename'];
    $thumb='./Uploads/'.$info['Filedata']['savepath'].$_SESSION['uid'].'thumb'.'.'.'jpg';
    $images=new \Think\Image();
    $images->open($img);
    $images->thumb(160, 160,\Think\Image::IMAGE_THUMB_CENTER)->save($thumb);
     //缩略图
    echo json_encode(array('img'=>$img,'thumb'=>$thumb,'status'=>1));
    }else{
    echo json_encode(array('status'=>0,'msg'=>'失败'));
   }
   
}
/**
 * 用户头像修改
 * @return [type] [description]
 */
public function facesave(){
  if(empty($_POST)){
    $this->error('请选择照片');
  }
    $userinfo=D('userinfo');
    $data=array('face'=>$_POST['i'],'thumb'=>$_POST['thumb']);
    $where=array('uid' =>$_SESSION['uid']);
    $result=$userinfo->where($where)->find();
    if($result){
      $_info=$userinfo->where($where)->save($data);
      if(false!==$_info){
        $this->success('修改成功','userinfo');
      }else{
        $this->error('修改失败');
      }
      }else{
        $data['uid']=$_SESSION['uid'];
        $userinfo->add($data);
      }
     }
     /**
      * 密码修改
      * @return [type] [description]
      */
     public function passchange(){
      $where=array('email'=>$_POST['email']);
      $conf=array(
        array('old','require','密码必须填写'),
        array('new','require','密码必须填写'),
        array('new_comfirm','new','确认密码不正确',0,'comfirm')
        );
      $user=D('user');
      if(!$user->create($conf)){
          $this->error('修改失败');
      }
      $info=$user->where($where)->find();
      if($info){
        if($info['password']==encryption($_POST['old'])){
             $data=array('password'=>encryption($_POST['new']));
             $info_=$user->where($where)->save($data);
             if($info){
              $this->success('修改成功','userinfo');
             }else{
              $this->error('修改失败');
             }
        }
      }
      else{
        $this->error('修改失败');
      }
     }
     /**
      * 说说发表
      * @return [type] [description]
      */
     public function usersay(){
      if(empty($_FILES)){
      if(empty($_POST['content'])){
        $this->error('请输入内容');
      }}
      $_POST['content']=htmlspecialchars($_POST['content']);
      $usersay=D('weibo');
      $_POST['time']=date('Y-m-d H:i:s');
      $_POST['uid']=$_SESSION['uid'];
      $info=$usersay->create();
      if(!$info){
        $this->error('操作失败');
      }
      $info=$usersay->add();
      if($info){
        if($_FILES['pictures']['error']!==4){
          $type=explode('/',$_FILES['pictures']['type']);
          $type=$type[1];
          move_uploaded_file($_FILES['pictures']['tmp_name'], './Uploads/pictures/'.$_FILES['pictures']['name']);
          $pictures=new \Think\Image();
          $pictures->open('./Uploads/pictures/'.$_FILES['pictures']['name'])->text($_SESSION['username'],'./ttf/1.ttf',15,'#FAFAFA',\Think\Image::IMAGE_WATER_SOUTH)->save('./Uploads/pictures/'.$info.'.'.$type); 
          unlink('./Uploads/pictures/'.$_FILES['pictures']['name']);
          if(M('picture')->data(array('pictures'=>'./Uploads/pictures/'.$info.'.'.$type,'wid'=>$info))->add()){
            $this->success('操作成功',U('Index/index'));
          }
        }else{
          $this->success('操作成功',U('Index/index'));
        }
      }else{
        $this->error('操作失败');
      }
     }
     //用户个人主页
     public function userhome(){
      if(empty($name)){
        $uid=$_SESSION['uid'];
      }else{
      $name=$_GET['name'];
      $uid=M('userinfo')->where(array('nikename'=>$name))->getField('uid');
      if(empty($uid)){
        $uid=M('user')->where(array('username'=>$name))->getField('id');
      }
      }
      //判断用户的行为（1：查看个人主页；2：查看说说；3；查看收藏，4：查看相册）
      switch ($_GET['status']) {
        case '1':
         $info=M('userinfo')->where(array('uid'=>$uid))->find();
      if($info){
        if(empty($info['nikename'])){
          $info['nikename']=M('user')->where(array('id'=>$uid))->getField('username');
        }
        if(empty($info['thumb'])){
         $info['thumb']=C('default_face');
        }
      }
      if(!empty($info['location'])){
        $info['location']=str_replace('+', '-', $info['location']);
      }
        $info['thumb']=__ROOT__.$info['thumb'];
        $where=array('uid'=>$uid);
        $this->assign('status',1);
        $this->assign('userinfo',$info);
          break;
        case '2':
        $where=array('uid'=>$uid);
          break;
        case '3':
        $collect=M('collect')->where(array('uid'=>$uid))->order('time DESC')->getField('wid',true);
        $where=array('id'=>array('IN',$collect));
            break;
        case '4':
              $where=array('uid'=>$uid,'pictures'=>array('NEQ','NULL'));
              $this->assign('status',4);
              break;
        default:
          $this->error('操作有误');
          break;
      }
        $say= new \Home\Model\IndexModel();
        $count=$say->where($where)->count();
        $page=new \Think\Page($count,15);
        $show=$page->show();
        $list = $say->where($where)->order('time DESC')->limit($page->firstRow.','.$page->listRows)->select();
        $list=showsay($list);
      $this->assign('list',$list);
      $this->assign('page',$show);
      $this->display();
     }
     public function letter(){
      $this->redirect('Index/index',array(),3,'私信功能正在开发中');
     }
}