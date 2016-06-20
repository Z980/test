<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index(){
        //说说分页输出
        $say= new \Home\Model\IndexModel();
        $count=$say->count();
        $page=new \Think\Page($count,15);
        $show=$page->show();
        $list = $say->order('time DESC')->limit($page->firstRow.','.$page->listRows)->select();
        $list=showsay($list);
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
}
/**
 * *ajax点赞与取消点赞
 * @return [boolean] [description]
 */
public function like(){
  if(IS_AJAX){
    $_POST['uid']=$_SESSION['uid'];
    if($_POST['status']==1){
        if(M('likes')->where(array('uid'=>$_POST['uid'],'wid'=>$_POST['wid']))->find()){
        echo 'false';
        }else{ $info=M('likes')->add($_POST);
          if($info){
            echo 'true';
           }else{
            echo 'false';
                }
        }
    }else{
      if(M('likes')->where(array('uid'=>$_POST['uid'],'wid'=>$_POST['wid']))->delete()){
        echo 'true';
      }else{
        echo 'false';
      }
    } 
       
  }

}
/**
 * *ajax添加评论
 * @return [json] [返回添加的评论]
 */
public function addcomment(){
    if(IS_AJAX){
        $comment=M('comment');
        $_POST['contentes']=htmlspecialchars($_POST['comment']);
        $_POST['commenttime']=time();
        $comment->create();
        $info=$comment->add();
        if($info){
            $info=$comment->where(array('id'=>$info))->find();
            if($info){
                $info['contentes']=biaoqin($info['contentes']);
                $info['name']=showname($_SESSION['uid']);
                $info['commenttime']=date('Y-m-d H:s');
                echo json_encode($info);
            }
        }
    }
}
/**
 * ajax删除说说
 * @return [boolean] [删除是否成功]
 */
public function deletesay(){
     if(IS_AJAX){
        if(!empty($_POST['wid'])){
            $say=M('weibo');
            $info=$say->where(array('id'=>$_POST['wid']))->delete();
            if(false !==$info){
                $info=M('comment')->where(array('wid'=>$_POST['wid']))->delete();
                if(false!==$info){
                    echo "true";
                }else{
                    echo "false";
                }
            }else{
                echo "false";
            }

        }
     }
}
/**
 * ajax删除评论
 * @return [boolean] [是否删除成功]
 */
public function deletecomment(){
    if(IS_AJAX){
        if(!empty($_POST['id'])){
            $info=M('comment')->where(array('id'=>$_POST['id']))->delete();
            if($info){
                echo 'ture';
            }else{
                echo 'false';
            }
        }else{
            echo 'false';
        }
    }
}
public function collect(){
  if(IS_AJAX){
    $_POST['uid']=$_SESSION['uid'];
    if($_POST['status']==1){
        if(M('collect')->where(array('uid'=>$_POST['uid'],'wid'=>$_POST['wid']))->find()){
        echo 'false';
        }else{ 
            $_POST['time']=time();
            $info=M('collect')->add($_POST);
          if($info){
            echo 'true';
           }else{
            echo 'false';
                }
        }
    }else{
      if(M('collect')->where(array('uid'=>$_POST['uid'],'wid'=>$_POST['wid']))->delete()){
        echo 'true';
      }else{
        echo 'false';
      }
    } 
       
  }

}
public function bugfind(){
    if(IS_POST){
        if(!empty($_POST['content'])){
           $_POST['content']=htmlspecialchars( $_POST['content']);
           $bug=M('bug');
           $bug->time=time();
           $bug->create();
           if($bug->add()){
            $this->success('感谢您的意见',U('Index/index'));
           }
        }
    }
    $this->display();
}

}