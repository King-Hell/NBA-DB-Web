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
        <label>中文名*</label>
        <input id="name" type="text" class="form-control" required>
    </fieldset>
    <fieldset class="form-group">
        <label>球队</label>
        <select id="team_name" class="form-control">
            <option>自由球员</option>
            <?php
            $teamlist = $mysqli->query("select name from teams");
            while ($row = $teamlist->fetch_row()) {
                echo "<option value='$row[0]'>$row[0]</option>";
            }
            ?>
        </select>
    </fieldset>
    <fieldset class="form-group">
        <label>英文名</label>
        <input id="english_name" type="text" class="form-control">
    </fieldset>

    <fieldset class="form-group">
        <label>出生城市</label>
        <input id="birthcity" type="text" class="form-control">
    </fieldset>
    <fieldset class="form-group">
        <label>生日</label>
        <input id="born" type="date" class="form-control">
    </fieldset>
    <fieldset class="form-group">
        <label>高中</label>
        <input id="highschool" type="text" class="form-control">
    </fieldset>
    <fieldset class="form-group">
        <label>大学</label>
        <input id="university" type="text" class="form-control">
    </fieldset>
    <fieldset class="form-group">
        <label>职业生涯</label>
        <input id="career" type="text" class="form-control">
    </fieldset>
    <fieldset class="form-group">
        <label>常规赛战绩</label>
        <input id="regular" type="text" class="form-control">
    </fieldset>
    <fieldset class="form-group">
        <label>季后赛战绩</label>
        <input id="playoff" type="text" class="form-control">
    </fieldset>
    <fieldset class="form-group">
        <label>进入总决赛次数</label>
        <input id="final" type="text" class="form-control form-number">
    </fieldset>
    <fieldset class="form-group">
        <label>总冠军次数</label>
        <input id="champion" type="text" class="form-control form-number">
    </fieldset>
    <fieldset class="form-group">
        <label>最佳教练</label>
        <input id="best" type="text" class="form-control">
    </fieldset>
</form>
<div class="row">
    <button id="submit" class="btn btn-success col-2 offset-2">修改</button>
    <button id="clear" class="btn btn-danger col-2 offset-2">删除</button>
</div>
<script>
    $(".form-number").keyup(function () {
        var c = $(this);
        if (/[^\d]/.test(c.val())) {//替换非数字字符
            var temp_amount = c.val().replace(/[^\d]/g, '');
            $(this).val(temp_amount);
        }
    });

    $('#clear').click(function () {
        if (confirm("确定删除该教练？"))
            $.post('main.php', {type: 'delete-coach', data: $('#hide-id').text()}, function (response) {
                if (response == 'SUCCESS')
                    alert('删除教练成功');
                else
                    alert('删除教练失败');
            })
    });

    $('#submit').click(function () {
        var input = $('input[id!="content"]');
        if (input.eq(0).val() == "") {
            alert('姓名不能为空');
            return;
        }
        $.post('main.php', {type: 'delete-coach', data: $('#hide-id').text()});
        var data = "{";
        data += '"id":"' + $('#hide-id').text() + '",';
        for (var i = 0; i < input.length; i++) {
            var e = input.eq(i);
            if (e.val() != '')
                data += '"' + e.attr('id') + '":"' + e.val() + '",';
        }
        data += '"team_name":"' + $('#team_name').val() + '",';
        data = data.substr(0, data.length - 1);
        data += '}';
        $.post("main.php", {type: 'add-coach', data: data}, function (response) {
            if (response == 'SUCCESS')
                alert('修改教练成功');
            else
                alert('修改教练失败');
        });
    })

    $('#search').click(function () {
        if ($('#content').val() != '')
            $.post('main.php', {type: 'search-coach', data: $('#content').val()}, function (response) {
                $('#result').html(response);
            })
    })


</script>
