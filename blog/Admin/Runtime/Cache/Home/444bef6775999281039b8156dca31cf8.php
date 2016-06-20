<?php if (!defined('THINK_PATH')) exit();?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理员登陆</title>
<style type="text/css">
@charset "utf-8";
html *{margin:0;padding:0;font-family:Microsoft Yahei;font-size14px;}
body{text-align:center;background:#f1f1f1;}
a{color:#999;font-size:12px;}

/* login */
.login{margin:0 auto;margin-top:100px;width:330px;height:220px;text-align:left;border:solid #ccc 1px;padding:10px 0;background:#fff;border-radius:5px;box-shadow:2px 2px 2px #ccc;}
.login label{display:block;padding:0 20px;text-transform:capitalize;height22px;line-height:22px;margin-top:10px;position:relative;}
.login label a{position:absolute;right:25px;top:0px;}
.login input{margin-left:15px;width:295px;height:30px;border:solid #ccc 1px;border-radius:5px;margin-top:2px;}
.login span{width:95%;margin:20px auto;height:1px;overflow:hidden;background:#ccc;display:block;}
.login input[type=checkbox]{width:15px;height:15px;border-radius:1px;background:linear-gradient(#fff,#ccc);margin-top:13px;float:left;border:solid #ccc 1px;}
.login input[type=checkbox] + label{font-size:12px;color:red;float:left;height:20px;line-height:20px;padding:0;padding-left:10px;}
.login input[type=submit]{width:100px;height:30px;line-height:30px;float:right;border-radius:15px;margin-right:20px;border:solid #7dcdf2 1px;font-size:16px;background:linear-gradient(#b9ddf3,#7dcdf2);box-shadow:2px 2px 2px #7dcdf2;color:#fff;cursor:position;*background:#b9ddf3;background:#b9ddf3\0;}
.login input[type=submit]:active{position:relative;left:1px;top:1px;color:red;}
</style>
</head>
<body>
<div class="login">
	<form action="/blog/admin.php/Home/Login/login.html" method="post">
		<label>用户名:</label>
		<input type="text" name="username" required />
		
		<label>密码:</label>
		<input type="password" name="password" required />
		<span></span>
		<input type="submit" value="登录" />
	</form>
</div>

</body>
</html>