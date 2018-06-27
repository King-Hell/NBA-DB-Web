<?php
/**
 * Created by PhpStorm.
 * User: hasee
 * Date: 2018/6/27
 * Time: 10:11
 */
if(isset($_GET['logout'])){
    session_start();
    session_unset();
    session_destroy();
    exit();
}

if(!isset($_POST['username'])||!isset($_POST['password'])){
    echo "FAIL";
    exit();
}
$username=$_POST['username'];
$password=$_POST['password'];
$mysqli = new mysqli("localhost", $username, $password, "basketballdb");
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    echo "DENIED";
    exit();
}
session_start();
$_SESSION['login']=1;
$_SESSION['username']=$username;
$_SESSION['password']=$password;
echo "SUCCESS";
?>