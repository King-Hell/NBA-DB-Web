<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>球队资料</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/default.css" rel="stylesheet">
    <link href="images/ico.ico" rel="shortcut icon">
    <link rel="stylesheet" href="http://ico.z01.com/zico.min.css" rel="stylesheet">
</head>

<body>
<?php
$mysqli = new mysqli("localhost", "visitor", "1234", "basketballdb");
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}
if (isset($_GET["name"])) {
    $team_name = $_GET["name"];
    $result = $mysqli->query("select * from teams where name='$team_name'")->fetch_array();
    $id = $result['id'];
} else {
    $id = $_GET["id"];
    $result = $mysqli->query("select * from teams where id=$id")->fetch_array();
    $team_name = $result['name'];
}
$coach_name = $result['head_coach'];
$players = $mysqli->query("select number,id,name,position,height,weight,TIMESTAMPDIFF(YEAR, born, CURDATE()) as age,draft_year,salary from players where team_name='$team_name' order by salary desc");
$coach_id = $mysqli->query("select id from coaches where name='$coach_name'")->fetch_row()[0];
$data = $mysqli->query("select * from game_info where home='$team_name' or away='$team_name' order by date desc");
$month_list = $mysqli->query("SELECT\n" .
    "	DATE_FORMAT( date, '%Y-%m' ) AS MONTH,\n" .
    "	count( * ) \n" .
    "FROM\n" .
    "	game_info\n" .
    "WHERE away='$team_name' or home='$team_name'\n" .
    "GROUP BY\n" .
    "MONTH \n" .
    "ORDER BY\n" .
    "MONTH DESC");
$avg_pre = $mysqli->query("select * from team_season where team_name='$team_name' and type='季前赛'");
$avg_reg = $mysqli->query("select * from team_season where team_name='$team_name' and type='常规赛'")->fetch_row();
$avg_off = $mysqli->query("select * from team_season where team_name='$team_name' and type='季后赛'");
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top"><a class="navbar-brand" href="#"><img alt="NBA标志"
                                                                                                         src="images/nba_logo.png"
                                                                                                         style="height: 32px;width: 54px;vertical-align: top">
        NBA数据库</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span
                class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active"><a class="nav-link" href="index.html">主页 </a>
            </li>
            <li class="nav-item"><a class="nav-link" href="team-index.html">球队</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="player-index.php">球员</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="coach-index.php">教练</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="game-index.php">赛程</a>
            </li>
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
            <div class="card col-lg-4"><img id="team-pic" src="images/team/<?php echo $id; ?>.png" alt="球队标志">
                <div class="card-body">
                    <h3 class="card-title">
                        <?php echo $result['city'], " ", $result['name'] ?>
                    </h3>
                    <h5 class="card-subtitle team-info-1">
                        <?php echo $result['english_city'], " ", $result['english_name']; ?>
                    </h5>
                    <p class="card-text">
                        <?php echo $result['conference'], ' | ', $result['division']; ?>
                    </p>
                </div>
            </div>
            <div class="card col-lg-4">
                <div class="card-body">
                    <h4 class="card-title team-info-2">球队信息</h4>
                    <p class="card-text team-info-3">
                        <?php echo '成立时间：', $result['founded'], '年<br>所属地区：', $result['location'], '<br>主场馆：', $result['arena'], "<br>主教练：<a href='coach.php?id=$coach_id'>", $result['head_coach'], '</a>'; ?>
                    </p>
                </div>
            </div>
            <div class="card col-lg-4">
                <div class="card-body">
                    <h4 class="card-title team-info-2">球队简介</h4>
                    <p class="card-text team-info-4">
                        <?php echo $result['description']; ?>
                    </p>
                </div>
            </div>

        </div>
    </div>
    <div class="container">
        <div class="card">
            <div id="team-bar" style="width: 100%;height: 400px;">
            </div>

        </div>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="text-blue">球员资料</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped table-players">
                        <thead class="thead-light">
                        <tr>
                            <th>号码</th>
                            <th>头像</th>
                            <th>姓名</th>
                            <th>位置</th>
                            <th>身高(cm)</th>
                            <th>体重(kg)</th>
                            <th>年龄</th>
                            <th>选秀年份</th>
                            <th>薪资(万美元)</th>
                        </tr>
                        </thead>
                        <?php
                        while ($row = $players->fetch_row()) {
                            echo "<tr>";
                            echo "<td>", $row[0], '</td>';
                            echo "<td>", "<img class='img-thumbnail' alt='头像' src='images/player/$row[1].png'></td>";
                            echo "<td>", '<a href="player.php?id=', $row[1], '">', $row[2], '</a></td>';
                            for ($i = 3; $i <= 8; $i++) {
                                if ($row[$i] == Null)
                                    $row[$i] = "-";
                                echo "<td>", $row[$i], "</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card col-lg-12" id="data">
            <div id="player-data" class="card-header bg-white"><img alt="球队标志" class="float-left img-thumbnail"
                                                                    src="images/team/<?php echo $id ?>.png"> 当前赛季比赛记录
                <button class="btn btn-info" id="btn-all" data-span="false">展开全部</button>
                <img alt="球队标志" class="float-right img-thumbnail" src="images/team/<?php echo $id ?>.png">
            </div>

            <?php
            while ($row = $month_list->fetch_row()) {
                echo "<div class=\"card\">
                    <div class=\"card-header\">
                        <a class=\"card-link text-info\" data-toggle=\"collapse\" href=\"#collapse-$row[0]\">
                            <h5><i class=\"zi zi_doubleDown\"></i>&nbsp;$team_name&nbsp;$row[0]月数据&nbsp;<span class=\"badge badge-secondary\">$row[1]场比赛</span>&nbsp;<i class=\"zi zi_doubleDown\"></i></h5>
                        </a>
                    </div>
                    <div id=\"collapse-$row[0]\" class=\"collapse col-player\">
                        <div class=\"table-responsive\">                      
                            <table class='table table-sm table-bordered'>
                       <thead>
                       <tr>
                       <th>日期</th>
                       <th>对手</th>
                       <th>比分</th>
                       <th>投篮</th>
                       <th>投篮%</th>
                       <th>三分</th>
                       <th>三分%</th>
                       <th>罚球</th>
                       <th>罚球%</th>
                       <th>进攻篮板</th>
                       <th>防守篮板</th>
                       <th>助攻</th>
                       <th>犯规</th>
                       <th>抢断</th> 
                       <th>失误</th>
                       <th>盖帽</th>
                       </tr>
                       </thead>";

                for ($i = 0; $i < $row[1]; $i++) {
                    $r = $data->fetch_row();
                    echo "<tr>";
                    echo "<td><a href='game.php?id=$r[0]'>$r[1]</a></td>";
                    if ($r[3] == $team_name)
                        echo "<td><a href='team.php?name=$r[4]'>$r[4]</a></td>";
                    else
                        echo "<td><a href='team.php?name=$r[3]'>$r[3]</a></td>";
                    echo "<td>$r[5]-$r[6]";
                    if ($r[5] > $r[6] && $r[3] == $team_name || $r[5] < $r[6] && $r[4] == $team_name)
                        echo " 胜</td>";
                    if ($team_name == $r[4])
                        $x = 17;
                    else $x = 30;
                    for ($j = $x; $j <= $x + 5;) {
                        $a = $r[$j++];
                        $b = $r[$j++];
                        echo "<td>$a-$b</td>";
                        if ($b == 0)
                            $c = '-';
                        else
                            $c = round($a / $b, 3) * 100 . '%';
                        echo "<td>$c</td>";
                    }
                    for ($k = $x + 6; $k <= $x + 12; $k++)
                        echo "<td>$r[$k]</td>";
                }
                echo "</table></div></div></div>";

            }
            ?>

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
                            src="images/beian.png" style="float:left;" alt="公安部标志"/>
                    <p>鲁公网安备 37028202000232号</p>
                </a>
            </div>
        </div>
    </div>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    $('#btn-all').click(function () {
        if ($(this).attr('data-span') == "false") {
            $('.col-player').collapse('show')
            $(this).attr("data-span", "true")
            $(this).text("折叠全部")
            $('i').addClass('zi_doubleUp')
        } else {
            $('.col-player').collapse('hide')
            $(this).attr("data-span", "false")
            $(this).text("展开全部")
            $('i').removeClass('zi_doubleUp')
        }

    })
    $('.card-link.text-info').click(function () {
        $(this).find('i').toggleClass('zi_doubleUp')
    })
</script>
<script src="https://cdn.bootcss.com/echarts/4.1.0.rc2/echarts.min.js"></script>
<script>
    var myChart2 = echarts.init($('#team-bar')[0]);
    var option2 = {
        title: {
            text: '赛季平均数据总览',
            subtext: '点击右侧图例可切换显示内容'
        },
        legend: {
            data: ['季前赛', '常规赛', '季后赛']
        },
        tooltip: {
            trigger: 'axis'
        },
        xAxis: {
            type: 'category',
            axisTick: {
                show: false
            },
            axisPointer: {
                type: 'shadow'
            },
            data: ['胜场数', '负场数', '投篮%', '三分%', '罚球%', '前场篮板', '后场篮板', '助攻', '犯规', '抢断', '失误', '盖帽', '得分']
        },
        yAxis: {
            type: 'log',
            show: false
        },
        series: [{
            name: '季前赛',
            type: 'bar',
            label: {
                show: true,
                position: 'top'
            },
            data: [ <?php if ($row = $avg_pre->fetch_row()) {
                if ($row[16] != 0)
                    echo "$row[16],";
                else
                    echo ",";
                if ($row[17] != 0)
                    echo "$row[17],";
                else
                    echo ",";
                $fg = round($row[2] / $row[3], 3) * 100;
                $tp = round($row[4] / $row[5], 3) * 100;
                $ft = round($row[6] / $row[7], 3) * 100;
                echo "{value:$fg,formatter:'{c}%'},";
                echo "{value:$tp,formatter:'{c}%'},";
                echo "{value:$ft,formatter:'{c}%'},";

                for ($i = 8; $i <= 14; $i++)
                    echo "$row[$i],";
                echo "$row[15]";
            }
                ?> ]
        }, {
            name: '常规赛',
            type: 'bar',
            label: {
                show: true,
                position: 'top'
            },
            data: [ <?php $row = $avg_reg;
                echo "$row[16],$row[17],";
                $fg = round($row[2] / $row[3], 3) * 100;
                $tp = round($row[4] / $row[5], 3) * 100;
                $ft = round($row[6] / $row[7], 3) * 100;
                echo "{value:$fg,formatter:'{c}%'},";
                echo "{value:$tp,formatter:'{c}%'},";
                echo "{value:$ft,formatter:'{c}%'},";

                for ($i = 8; $i <= 14; $i++)
                    echo "$row[$i],";
                echo "$row[15]";
                ?> ]
        }, {
            name: '季后赛',
            type: 'bar',
            label: {
                show: true,
                position: 'top'
            },
            data: [ <?php if ($row = $avg_off->fetch_row()) {
                if ($row[16] != 0)
                    echo "$row[16],";
                else
                    echo ",";
                if ($row[17] != 0)
                    echo "$row[17],";
                else
                    echo ",";
                $fg = round($row[2] / $row[3], 3) * 100;
                $tp = round($row[4] / $row[5], 3) * 100;
                $ft = round($row[6] / $row[7], 3) * 100;
                echo "{value:$fg,formatter:'{c}%'},";
                echo "{value:$tp,formatter:'{c}%'},";
                echo "{value:$ft,formatter:'{c}%'},";

                for ($i = 8; $i <= 14; $i++)
                    echo "$row[$i],";
                echo "$row[15]";
            }
                ?> ]
        }]
    };
    myChart2.setOption(option2);
</script>
</body>
</html>