<?php
session_start();
if(isset($_SESSION['login'])) {
    if ($_SESSION['login'] == 1)
        header("Location:admin.php");
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>NBA数据查询系统后台</title>
<meta name="viewport" content="width=device-width">

<link href="css/login.css" rel="stylesheet" type="text/css">
</head>
<body>

<div class="login">
	<div class="logo"></div>
    <div class="login_form">
    	<div class="user">
        	<input class="text_value" value="" name="username" type="text" id="username">
            <input class="text_value" value="" name="password" type="password" id="password">
        </div>
        <button class="button" id="submit">登录</button>
    </div>
    
    <div id="tip"></div>
    <div class="foot">
    Copyright © 2018 LiTong All Rights Reserved.
    </div>
</div>
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
<script>
    $('#submit').click(function(){
        if($('#username').val()=="" || $('#password').val()==""){
            alert("用户名和密码不能为空");
        }else{
            $.post("login.php",{username:$('#username').val(),password:$('#password').val()},function (data,status,xhr) {
                if(data=="SUCCESS"){
                    alert('登陆成功');
                    $(location).attr('href', 'admin.php');
                }else if("DENIED")
                    alert('登陆失败，请检查用户名和密码');
                else
                    alert('服务器连接失败');
            })
        }
    });


</script>
</body>
</html>
