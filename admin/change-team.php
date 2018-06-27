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
<p id="hide-id" style="display: none"></p>
<div class="row"><input class="form-control col-7 offset-1" type="search" id="content"
                        placeholder="请输入要查找的教练，可输入部分中文或英文名" name="content" required="true">
    <button class="btn btn-outline-success col-2" id="search">搜索</button>
</div>
<div id="result"></div>
<form class="form-row">
    <fieldset class="form-group">
        <label>缩写*</label>
        <input id="abb" type="text" class="form-control" required>
    </fieldset>
    <fieldset class="form-group">
        <label>城市</label>
        <input id="city" type="text" class="form-control">
    </fieldset>
    <fieldset class="form-group">
        <label>球队名*</label>
        <input id="name" type="text" class="form-control">
    </fieldset>
    <fieldset class="form-group">
        <label>英文城市</label>
        <input id="english_city" type="text" class="form-control">
    </fieldset>
    <fieldset class="form-group">
        <label>英文名</label>
        <input id="english_name" type="text" class="form-control">
    </fieldset>
    <fieldset class="form-group">
        <label>联盟</label>
        <input id="conference" type="text" class="form-control">
    </fieldset>
    <fieldset class="form-group">
        <label>分区</label>
        <input id="division" type="text" class="form-control">
    </fieldset>
    <fieldset class="form-group">
        <label>成立年份</label>
        <input id="founded" type="text" class="form-control form-number">
    </fieldset>
    <fieldset class="form-group">
        <label>位置</label>
        <input id="location" type="text" class="form-control">
    </fieldset>
    <fieldset class="form-group">
        <label>球馆</label>
        <input id="arena" type="text" class="form-control">
    </fieldset>
    <fieldset class="form-group">
        <label>主教练</label>
        <input id="head-coach" type="text" class="form-control">
    </fieldset>

    <fieldset class="form-group form-long">
        <label>描述</label>
        <textarea id="description" class="form-control"></textarea>
    </fieldset>
</form>
<div class="row">
    <button id="submit" class="btn btn-success col-2 offset-2">修改</button>

</div>
<script>
    $(".form-number").keyup(function () {
        var c = $(this);
        if (/[^\d]/.test(c.val())) {//替换非数字字符
            var temp_amount = c.val().replace(/[^\d]/g, '');
            $(this).val(temp_amount);
        }
    });



    $('#submit').click(function () {
        var input = $('input[id!="content"]');
        $.post('main.php', {type: 'delete-team', data: $('#hide-id').text()});
        var data = "{";
        data += '"id":"' + $('#hide-id').text() + '",';
        for (var i = 0; i < input.length; i++) {
            var e = input.eq(i);
            if (e.val() != '')
                data += '"' + e.attr('id') + '":"' + e.val() + '",';
        }
        data = data.substr(0, data.length - 1);
        data += '}';
        $.post("main.php", {type: 'add-team', data: data}, function (response) {
            if (response == 'SUCCESS')
                alert('修改球队成功');
            else
                alert('修改球队失败');
        });
    })

    $('#search').click(function () {
        if ($('#content').val() != '')
            $.post('main.php', {type: 'search-team', data: $('#content').val()}, function (response) {
                $('#result').html(response);
            })
    })


</script>
