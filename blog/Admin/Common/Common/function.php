<?php
/**
 * *异位或加密
 * @param  [string]  $value [要加密解密的字符串]
 * @param  integer $type  [加密解密（0：加密，1：解密）]
 * @return [type]         [description]
 */
function encryption($value,$type=0){
         $key=md5(C('ENCRYPTION_KEY'));
         if($type){
         	return base64_decode($value) ^ $key;

         }else{
         	return base64_encode($value ^ $key);
         }
}
/**
 * *表情替换
 * @param  [string] $str [说说内容]
 * @return [type]      [description]
 */
function biaoqin($str){
	$str = str_replace ( ">", '<；', $str );	
    $str = str_replace ( ">", '>；', $str );
    $str = str_replace ( "\n", '>；br/>；', $str );	
    $str = preg_replace ( "[\[em_([0-9]*)\]]", "<img src=\"/blog/Public/images/arclist/$1.gif\" />", $str );
    return $str;
}
function showname($uid){
  $nikename=M('userinfo')->where(array('uid'=>$uid))->getField('nikename');
  if(!empty($nikename)){
    return $nikename;
  }else{
    return M('user')->where(array('id'=>$uid))->getField('username');
  }
}
function showsay($list){
  foreach ($list as $key => $value) {
            $list[$key]['content']=biaoqin($value['content']);
            $list[$key]['pictures']=$list[$key]['pictures']?__ROOT__.$list[$key]['pictures']:'';
            $list[$key]['likes']=implode(M('likes')->where(array('wid'=>$list[$key]['id']))->getField('uid',true));
            $list[$key]['like']=count($list[$key]['likes']);
            $list[$key]['collect']=implode(M('collect')->where(array('wid'=>$list[$key]['id']))->getField('uid',true));
            $list[$key]['commentes']=M('comment')->order('commenttime')->where(array('wid'=>$list[$key]['id']))->select();
            foreach ($list[$key]['commentes'] as $k=>  $value) {
                $list[$key]['commentes'][$k]['commentname']=showname($list[$key]['commentes'][$k]['uid']);
                $list[$key]['commentes'][$k]['contentes']=biaoqin($list[$key]['commentes'][$k]['contentes']);
            }
        }
        return $list;
}
