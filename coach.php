<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>教练资料</title>
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
if (!isset($_GET["id"])) {
    $id = 310;
} else {
    $id = $_GET["id"];
}
$result = $mysqli->query("select * from coaches where id='$id'")->fetch_assoc();
$name = $result['name'];
$team_id = $mysqli->query("select id from teams where head_coach='$name'")->fetch_row()[0];
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top"><a class="navbar-brand" href="#"><img alt="NBA标志"
                                                                                                         src="https://nba-1253437773.cos.ap-beijing.myqcloud.com/images/nba_logo.png"
                                                                                                         style="height: 32px;width: 54px;vertical-align: top">
        NBA数据库</a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link" href="index.html">主页 </a></li>
            <li class="nav-item"><a class="nav-link" href="team-index.html">球队</a></li>
            <li class="nav-item"><a class="nav-link" href="player-index.php">球员</a></li>
            <li class="nav-item active"><a class="nav-link" href="coach-index.php">教练</a></li>
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
<div class="jumbotron jumbotron-fluid text-center">
    <br>
    <div class="container card-deck-wrapper">
        <div class="card-deck">
            <div class="card col-lg-4">
                <img id="coach-pic" src="https://nba-1253437773.cos.ap-beijing.myqcloud.com/images/coach_big/<?php echo $result['id']; ?>.png" alt="教练头像">
                <div class="card-body">
                    <h3 class="card-title">
                        <?php echo $result['name'] ?>
                    </h3>
                    <h5 class="card-subtitle team-info-1">
                        <?php echo $result['english_name']; ?>
                    </h5>
                    <h4>
                        <small>
                            <?php echo '<a href="team.php?id=', $team_id, '">', $result['team_name'], '主教练</a>'; ?>
                        </small>
                    </h4>

                </div>
            </div>
            <div class="card col-lg-4">
                <div class="card-body">
                    <h4 class="card-title team-info-2">个人信息</h4>
                    <p class="card-text team-info-3">
                        <?php echo '出生日期：', $result['born'], '<br>出生城市：', $result['birthcity'], '<br>高中：', $result['highschool'], '<br>大学：', $result['university']; ?>
                    </p>
                </div>
            </div>
            <div class="card col-lg-4">
                <div class="card-body">
                    <h4 class="card-title team-info-2">执教经历</h4>
                    <p class="card-text team-info-3">
                        <?php echo '执教生涯：', $result['career'], '<br>常规赛：', $result['regular'], '<br>季后赛：', $result['playoff'], '<br>总决赛：', $result['final'], '次<br>总冠军：', $result['champion'], '个<br>最佳教练：', $result['best']; ?>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
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