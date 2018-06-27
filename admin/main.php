<?php
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
function add($type)
{
    $data = json_decode($_POST['data']);
    $col = "";
    $val = "";
    foreach ($data as $key => $value) {
        $col = $col . "$key,";
        $val = $val . "'$value',";
    }
    $col = chop($col, ',');
    $val = chop($val, ',');
    if ($GLOBALS['mysqli']->query("insert into $type($col) values($val);"))
        echo "SUCCESS";
    else
        echo "FAIL";
}
function select($type){
    $id = $_POST['data'];
    $result =$GLOBALS['mysqli']->query("select * from $type where id=$id")->fetch_row();
    echo '",';
    for ($i = 1; $i < count($result); $i++) {
        echo "\"$result[$i]\",";
    }
    echo '"';
}
function delete($type){
    $id = $_POST['data'];
    if($GLOBALS['mysqli']->query("delete from $type where id=$id"))
        echo "SUCCESS";
    else
        echo "FAIL";
}
function search($type){
    $name = $_POST['data'];
    $result = $GLOBALS['mysqli']->query("select id,name,english_name,team_name from $type where name like'%$name%' or english_name like'%$name%'");
    while ($row = $result->fetch_row()) {
        echo "<button class='id btn btn-primary' value='$row[0]'>$row[1],$row[2],$row[3]</button>";
    }
    echo "<script>";
    echo " $('.id').click(function () {
        $('#hide-id').text($(this).attr('value'));
        $.post('main.php',{type:'select-$type',data:$(this).attr('value')},function(response){
            str=response.split('\",\"');
            for(var i=1;i<str.length-1;i++){
                $('.form-control').eq(i).val(str[i]);
            }
        });
        });";

    echo "</script>";
}
switch ($_POST['type']) {
    case    'add-coach':
        add('coaches');
        break;
    case 'select-coaches':
        select('coaches');
        break;
    case 'delete-coach':
        delete('coaches');
        break;
    case 'search-coach':
        search('coaches');
        break;
    case    'add-player':
        add('players');
        break;
    case 'select-players':
        select('players');
        break;
    case 'delete-player':
        delete('players');
        break;
    case 'search-player':
        search('players');
        break;
    case    'add-team':
        add('teams');
        break;
    case 'select-team':
        select('teams');
        break;
    case 'delete-team':
        delete('teams');
        break;
    case 'search-team':
        $name = $_POST['data'];
        $result = $mysqli->query("select id,name,english_name from teams where name like'%$name%' or english_name like'%$name%'")->fetch_row();
        echo "<button class='id btn btn-primary' value='$result[0]'>$result[1],$result[2]</button>";
        echo "<script>";
        echo " $('.id').click(function () {
        $('#hide-id').text($(this).attr('value'));
        $.post('main.php',{type:'select-team',data:$(this).attr('value')},function(response){
            str=response.split('\",\"');
            for(var i=1;i<str.length-1;i++){
                $('.form-control').eq(i).val(str[i]);
            }
        });
        });";

        echo "</script>";
        break;
    case 'change-password':
        $password=$_POST['data'];
        if($mysqli->query("set password for admin@localhost = password($password);"))
            echo "SUCCESS";
        else
            echo "FAIL";
        break;

}
?>