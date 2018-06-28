<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>搜索结果</title>
    <!-- Bootstrap -->
    <link href="https://cdn.bootcss.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/default.css" rel="stylesheet">
    <link href="https://nba-1253437773.cos.ap-beijing.myqcloud.com/images/ico.ico" rel="shortcut icon">
</head>

<body>
<?php
$mysqli = new mysqli("localhost", "visitor", "1234", "basketballdb");
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

if (isset($_GET["type"]))
    $type = $_GET["type"];
else
    $type = '球队';
if (isset($_GET["content"]))
    $content = $_GET["content"];
else
    $content = '勇士';
switch ($type) {
    case '球员':
        $result = $mysqli->query("select id,name,english_name,team_name from players where name like'%$content%' or english_name like'%$content%'");
        break;
    case '球队':
        $result = $mysqli->query("select id,concat(city,' ',name),concat(english_city,' ',english_name) from teams where name ='$content' or english_name ='$content'");
        break;
    case '教练':
        $result = $mysqli->query("select id,name,english_name,team_name from coaches where name like'%$content%' or english_name like'%$content%'");
        break;
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top"><a class="navbar-brand" href="#"><img alt="NBA标志"
                                                                                                         src="https://nba-1253437773.cos.ap-beijing.myqcloud.com/images/nba_logo.png"
                                                                                                         style="height: 32px;width: 54px;vertical-align: top">
        NBA数据库</a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link" href="index.html">主页 </a>
            </li>
            <li class="nav-item"><a class="nav-link" href="team-index.html">球队</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="player-index.php">球员</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="coach-index.php">教练</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="game-index.php">赛程</a></li>
        <li class="nav-item"> <a class="nav-link" href="compare.php">对比</a></li></ul>
        <form action="search.php" target="_blank" class="form-inline">
            <div id="search-form" class="input-group col-10">
                <select class="form-control col-4" name="type">
                    <option value="球员">球员</option>
                    <option value="球队">球队</option>
                    <option value="教练">教练</option>
                </select>
                <input class="form-control col-8" type="search" placeholder="键入搜索内容" name="content" required="true">
            </div>
            <button class="btn btn-outline-success col-2" type="submit">搜索</button>
        </form>
    </div>
</nav>
<br>
<br>
<br>
<div class="container">
    <div class="card text-center">
        <div class="card-header">
            <h3>搜索结果</h3>
        </div>
        <div class="card-body">
            <h5 class="card-title">您搜索的内容为：<?php echo $type, "——", $content; ?></h5>

            <?php
            if ($result->num_rows == 0)
                echo "<p class='text-danger'>未找到任何结果，请检查您的输入或减少查询字符数量</p>";
            else {
                echo "<table class='table table-hover table-players'><thead class='thead-light'><tr>";
                switch ($type) {
                    case '球员':
                        echo "<th>头像</th><th>姓名</th><th>英文名</th><th>球队</th>";
                        $type = 'player';
                        break;
                    case '球队':
                        echo "<th>队标</th><th>球队名</th><th>英文名</th>";
                        $type = 'team';
                        break;
                    case '教练':
                        echo "<th>头像</th><th>姓名</th><th>英文名</th><th>球队</th>";
                        $type = 'coach';
                        break;
                }
                echo "</tr></thead>";
                while ($row = $result->fetch_row()) {
                    echo "<tr>";
                    echo "<td><a href='$type.php?id=$row[0]'><img alt='图片' height='90px' src='https://nba-1253437773.cos.ap-beijing.myqcloud.com/images/$type/$row[0].png'></a></td>";
                    for ($i = 1; $i < count($row); $i++) {
                        echo "<td><a href='$type.php?id=$row[0]'>$row[$i]</a></td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            }
            ?>

        </div>
        <div class="card-footer text-muted">为您找到
            <?php echo $result->num_rows; ?>条结果
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row">
        <div class="text-center col-lg-6 offset-lg-3">
            <h5 class="copyright">版权信息</h5>
            <p class="copyright">Copyright &copy; 2018 LiTong. All Rights Reserved. </p>
            <div id="beian">
                <a target="_blank" href="http://www.miitbeian.gov.cn/publish/query/indexFirst.action">
                    <p>鲁ICP备18002100号</p>
                </a> <a target="_blank"
                        href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=37028202000232"><img
                            src="https://nba-1253437773.cos.ap-beijing.myqcloud.com/images/beian.png" style="float:left;" alt="公安部标志"/>
                    <p>鲁公网安备 37028202000232号</p>
                </a></div>
        </div>
    </div>
</div>
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/popper.js/1.14.3/popper.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</body>
</html>