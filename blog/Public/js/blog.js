$(document).ready(function(){
   $('#up').hide();
  $('#clear').hide();
  $('#totop').hide();
  $(window).scroll(function(){
    if($(window).scrollTop()>$(window).height()){
    $('#totop').fadeIn(400);
  }else{
    $('#totop').fadeOut(400);
  }
  });
  $('#totop').click(function(){
    $('html,body').animate({scrollTop : '0px'}, 200);
  });
    $('.emotion1').on('click',function(){
      $('#comment').removeAttr('id');
      var thisspan=$(this);
      thisspan.parent().find('input[name="comment"]').attr('id','comment');
      $('.emotion1').qqFace({
    id : 'facebox', 
    assign:'comment', 
    path:'/blog/Public/images/arclist/' //表情存放的路径
  });
    });
    //添加评论
    $('.addcomment').on('click',function(){
      var parent=$(this).parent();
      var comment=parent.find('input[name="comment"]').val();
      var wid=parent.find('input[name="wid"]').val();
      if(comment!=='' && uid!=='' && wid!==''){
        $.ajax({
          url:addcomment,
          type:'post',
          dataType:'json',
          data:{
            comment:comment,
            uid:uid,
            wid:wid
          },
          cache:false,
          success:function(data){
            comment=parent.find('input:text').val('');
              eval(data);
              parent.parent().find('.commentlist:last').after('<li class="commentlist">'+data.name+'&nbsp;:&nbsp;'+data.contentes+'&nbsp;&nbsp;&nbsp;&nbsp;'+'<span class="commenttime">'+data.commenttime+'</span><img  class="deletecomment" style="width:18px;height:18px;cursor:pointer" src="/blog/Public/images/delete.png"></li>');
          }
        });
      }   
  });
    //删除说说
    $('.delete img').on('click',function(){
      var wid=$(this).parent().parent().parent().find('input[name="wid"]').val();
      var index=$(this);
      $.ajax({
        url:deletesay,
        type:'post',
        data:{wid:wid},
        success:function(data){
          if(data){
          index.parent().parent().parent().remove();
        }
        }
      });
    });
    //删除评论
    $('.deletecomment').on('click',function(){
      var thiscomment=$(this);
      var comment=thiscomment.parent().find('input').val();
      $.ajax({
        url:deletecomment,
        type:'post',
        data:{id:comment},
        success:function(data){
            if(data){
             thiscomment.parent().remove();
            }
        }
      });
    });
    //点赞
    $('.addlike').on('click',function(){
      var thislike=$(this);
      var wid=thislike.parent().find('input[name="wid"]').val();
      var likes=thislike.parent().find('p span').text();
      if(thislike.text()=='赞'){
      $.ajax({
        url:like,
        type:'post',
        data:{uid:uid,status:1,wid:wid},
        success:function(data){
          if(data){
            thislike.text('取消点赞');
            likes++;
            thislike.parent().find('p span').text(likes);
          }
        }
      });
    }else{
      $.ajax({
        url:like,
        type:'post',
        data:{uid:uid,status:0,wid},
        success:function(data){
          if(data){
            thislike.text('赞');
            likes--;
            thislike.parent().find('p span').text(likes);
          }
        }
      });
    }
    });
    //ajax收藏取消收藏
    $('.addcollect').on('click',function(){
      var thiscollect=$(this);
      var wid=thiscollect.parent().find('input[name="wid"]').val();
      if(thiscollect.text()=='收藏'){
      $.ajax({
        url:collect,
        type:'post',
        data:{uid:uid,status:1,wid:wid},
        success:function(data){
          if(data){
            thiscollect.text('取消收藏');
          }
        }
      });
    }else{
      $.ajax({
        url:collect,
        type:'post',
        data:{uid:uid,status:0,wid},
        success:function(data){
          if(data){
            thiscollect.text('收藏');
          }
        }
      });
    }
    });
});