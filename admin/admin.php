<?php
/**
 * Created by PhpStorm.
 * User: hasee
 * Date: 2018/6/27
 * Time: 10:38
 */
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] != 1) {
    header("HTTP/1.1 401 Unauthorized");
    echo("拒绝访问，<a href='index.php'>请点击此处进入登录页面</a>");
    exit();
}
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$mysqli = new mysqli("localhost", $username, $password, "basketballdb");
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    echo "服务器连接失败，请与管理员联系";
    exit();
}
?>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>后台管理</title>
    <link href="https://cdn.bootcss.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/default.css" rel="stylesheet">
</head>
<body>
<div id="main">
</div>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark fixed-top">
    <div class="navbar-header">
        <div class="navbar-brand">后台管理</div>
    </div>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto"></ul>
        <ul class="navbar-nav">
            <li class="nav-item nav-link">
                当前用户：<span class="text-primary"><?php echo $username ?></span>
            </li>
            <li class="nav-item">
                <button class="btn nav-link btn-outline-dark text-danger" id="logout">退出登录</button>
            </li>
        </ul>
    </div>

</nav>
<div id="left-nav" class="border bg-light">
    <ul class="list-group ">
        <li class="btn list-group-item text-left" id="add-player">增加球员</li>
        <li class="btn list-group-item text-left" id="add-coach">增加教练</li>
        <li class="btn list-group-item text-left" id="change-player">修改/删除球员信息</li>
        <li class="btn list-group-item text-left" id="change-coach">修改/删除教练信息</li>
        <li class="btn list-group-item text-left" id="change-team">修改球队信息</li>
        <li class="btn list-group-item text-left" id="change-password">修改密码</li>
    </ul>
</div>
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/popper.js/1.14.3/popper.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script>
    $('#logout').click(function () {
        $.get("login.php", {logout: 1}, function () {
            alert('您已成功退出');
            $(location).attr('href', 'index.html');
        })
    });

    $('#left-nav li').click(function () {
        $.get($(this).attr('id')+".php",function (data) {
            $('#main').html(data);
        })
    });
</script>
</body>
</html>
