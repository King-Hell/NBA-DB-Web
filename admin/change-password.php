<?php
/**
 * Created by PhpStorm.
 * User: hasee
 * Date: 2018/6/27
 * Time: 12:22
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
<fieldset class="form-group">
    <label>请输入新密码</label>
    <input id="password1" type="password" class="form-control">
</fieldset>
<fieldset class="form-group">
    <label>请确认密码</label>
    <input id="password2" type="password" class="form-control">
</fieldset>
<button class="btn btn-danger">修改密码</button>
<script>
    $('button').click(function () {
        if($('#password1').val()!=$('#password2').val()){
            alert('两次密码不相同，请重新输入');
            return;
        }
        $.post('main.php',{type:'change-password',data:$('#password1').val()},function (response) {
            if(response=='SUCCESS')
                alert('密码修改成功');
            else
                alert("密码修改失败");
        })
    })
    </script>
