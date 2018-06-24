<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>球员资料</title>
    <!-- Bootstrap -->
    <link href="css/default.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
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
if (!isset($_GET["id"])) {
    $id = 3311;
} else {
    $id = $_GET["id"];
}
$result = $mysqli->query("select * from players where id=$id")->fetch_assoc();
$data = $mysqli->query("SELECT\n" .
    "	* \n" .
    "FROM\n" .
    "	( SELECT id AS game_id, date, away, home FROM games ) AS t\n" .
    "	NATURAL JOIN player_record \n" .
    "WHERE\n" .
    "	player_id = $id \n" .
    "ORDER BY\n" .
    "	date DESC");
$month_list = $mysqli->query("SELECT\n" .
    "	DATE_FORMAT( date, '%Y-%m' ) AS MONTH,\n" .
    "	count( * ) \n" .
    "FROM\n" .
    "	( SELECT id AS game_id, date FROM games ) AS t\n" .
    "	NATURAL JOIN ( SELECT game_id FROM player_record WHERE player_id = $id ) AS s \n" .
    "GROUP BY\n" .
    "MONTH \n" .
    "ORDER BY\n" .
    "MONTH DESC");
$team_name = $result['team_name'];
$team = $mysqli->query("select id,city from teams where name='$team_name'")->fetch_row();
$legend_avg = $mysqli->query("select * from legend_avg")->fetch_row();
$player_avg = $mysqli->query("select pts,(orb+drb),ast,stl,blk from player_season where player_id=$id and type='常规赛'")->fetch_row();
$avg_pre = $mysqli->query("select * from player_season where player_id=$id and type='季前赛'");
$avg_reg = $mysqli->query("select * from player_season where player_id=$id and type='常规赛'")->fetch_row();
$avg_off = $mysqli->query("select * from player_season where player_id=$id and type='季后赛'");


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
            <li class="nav-item active"><a class="nav-link" href="index.html">主页 </a></li>
            <li class="nav-item"><a class="nav-link" href="team-index.html">球队</a></li>
            <li class="nav-item"><a class="nav-link" href="player-index.php">球员</a></li>
            <li class="nav-item"><a class="nav-link" href="coach-index.php">教练</a></li>
            <li class="nav-item"><a class="nav-link" href="game-index.php">赛程</a></li>
        </ul>
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
                <img id="player-pic" src="images/player_big/<?php echo $result['id']; ?>.png" alt="球员头像"
                     onerror="src='images/player_big/default.png'">
                <div class="card-body">
                    <h3 class="card-title">
                        <?php echo $result['name'] ?>
                    </h3>
                    <h5 class="card-subtitle team-info-1">
                        <?php echo $result['english_name']; ?>
                    </h5>
                    <h4>
                        <small>
                            <?php echo '<a href="team.php?id=', $team[0], '">', $team[1], $result['team_name'], '队球员</a>'; ?>
                        </small>
                    </h4>
                    <p class="lead">
                        <?php echo $result['number'], '号 | ', $result['position'] ?>
                    </p>


                </div>
            </div>
            <div class="card col-lg-4">
                <div class="card-body">
                    <h4 class="card-title team-info-2">个人信息</h4>
                    <p class="card-text player-info">
                        <?php echo '身高：', $result['height'], 'cm<br>体重：', $result['weight'], 'kg<br>出生日期：', $result['born'], '<br>学校：', $result['school'], '<br>国籍：', $result['nationality']; ?>
                        <?php echo '<br>选秀：', $result['draft'], '<br>本赛季薪资：', $result['salary'], '万美元<br>合同：', $result['contract']; ?>

                    </p>
                </div>
            </div>
            <div class="card col-lg-4 card-player-radar">
                <h4 class="card-title team-info-2">&nbsp;&nbsp;&nbsp;&nbsp;球员能力</h4>
                <div id="player-radar" style="height:100%;width: 100%">
                </div>
            </div>

        </div>

    </div>
    <div class="container">
        <div class="card">
            <div id="player-bar" style="width: 100%;height: 400px;">
            </div>
            <div class="card-footer"><?php echo"得分：联盟第$avg_reg[19]&emsp;篮板：联盟第$avg_reg[20]&emsp;助攻：联盟第$avg_reg[21]&emsp;抢断：联盟第$avg_reg[22]&emsp;盖帽：联盟第$avg_reg[23]"?></div>
        </div>
    </div>
        <br>
        <div class="container">
            <div class="card col-lg-12" id="data">
                <div id="player-data" class="card-header bg-white"><img alt="球队标志" class="float-left img-thumbnail"
                                                                        src="images/team/<?php echo $team[0] ?>.png">
                    当前赛季比赛记录
                    <button class="btn btn-info" id="btn-all" data-span="false">展开全部</button>
                    <img alt="球队标志" class="float-right img-thumbnail" src="images/team/<?php echo $team[0] ?>.png">
                </div>

                <?php
                $name = $result["name"];
                while ($row = $month_list->fetch_row()) {
                    echo "<div class=\"card\">
                    <div class=\"card-header\">
                        <a class=\"card-link text-info\" data-toggle=\"collapse\" href=\"#collapse-$row[0]\">
                            <h5><i class=\"zi zi_doubleDown\"></i>&nbsp;$name&nbsp;$row[0]月数据&nbsp;<span class=\"badge badge-secondary\">$row[1]场比赛</span>&nbsp;<i class=\"zi zi_doubleDown\"></i></h5>
                        </a>
                    </div>
                    <div id=\"collapse-$row[0]\" class=\"collapse col-player\">
                        <div class=\"table-responsive\">                      
                            <table class='table table-sm table-bordered'>
                       <thead>
                       <tr>
                       <th>日期</th>
                       <th>对手</th>
                       <th>首发</th>
                       <th>时间</th>
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
                       <th>得分</th>
                       <th>正负值</th>
                       </tr>
                       </thead>";

                    for ($i = 0; $i < $row[1]; $i++) {
                        $r = $data->fetch_row();
                        echo "<tr>";
                        echo "<td><a href='game.php?id=$r[0]'>$r[1]</a></td>";
                        if ($r[2] == $team_name)
                            echo "<td><a href='team.php?name=$r[3]'>$r[3]</a></td>";
                        else
                            echo "<td><a href='team.php?name=$r[2]'>$r[2]</a></td>";
                        if ($r[6] == 1)
                            echo "<td>是</td>";
                        else
                            echo "<td>否</td>";
                        echo "<td>$r[7]</td>";
                        for ($j = 8; $j <= 13;) {
                            $a = $r[$j++];
                            $b = $r[$j++];
                            echo "<td>$a-$b</td>";
                            if ($b == 0)
                                $c = '-';
                            else
                                $c = round($a / $b, 3) * 100 . '%';
                            echo "<td>$c</td>";
                        }
                        for ($k = 14; $k <= 21; $k++)
                            echo "<td>$r[$k]</td>";
                        if ($r[22] > 0)
                            echo "<td>+$r[22]</td>";
                        else
                            echo "<td>$r[22]</td>";
                        echo "</tr>";
                    }
                    echo "</table></div></div></div>";

                }
                ?>

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
                    </a></div>
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
    <script src="js/echarts.min.js"></script>
    <script>
        var myChart = echarts.init($('#player-radar')[0]);
        var option = {
            tooltip: {},
            legend: {
                data: ['个人数据', '联盟平均']
            },
            radar: {
                radius: '60%',
                type: 'log',
                name: {
                    textStyle: {
                        color: '#fff',
                        backgroundColor: '#999',
                        borderRadius: 3,
                        padding: [3, 5]
                    }
                },
                indicator: [
                    {name: '场均得分', max: 30.1},
                    {name: '场均篮板', max: 16},
                    {name: '场均助攻', max: 10.3},
                    {name: '场均抢断', max: 2.3},
                    {name: '场均盖帽', max: 2.6},
                ]
            },
            series: [{
                name: '数据统计',
                type: 'radar',
                data: [
                    {
                        value: [<?php for ($i = 0; $i < 5; $i++) echo "$legend_avg[$i],"?>],
                        name: '联盟平均',
                        areaStyle: {}
                    },
                    {
                        value: [<?php for ($i = 0; $i < 5; $i++) echo "$player_avg[$i],"?>],
                        name: '个人数据',
                        label: {show: true},
                        areaStyle: {color: '#337ab7'},
                        lineStyle: {color: '#2f4554'},
                        itemStyle: {color: '#2f4554'},
                    },

                ]
            }]
        };
        myChart.setOption(option);

        var myChart2 = echarts.init($('#player-bar')[0]);
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
                axisTick: {show: false},
                axisPointer: {type: 'shadow'},
                data: ['时间', '投篮%', '三分%', '罚球%', '前场篮板', '后场篮板', '助攻', '犯规', '抢断', '失误', '盖帽', '得分']
            },
            yAxis: {
                type: 'log',
                show:false
            },
            series: [{
                name: '季前赛',
                type: 'bar',
                label: {show: true, position: 'top'},
                data: [<?php if ($row = $avg_pre->fetch_row()) {
                    echo "$row[3],";
                    $fg = round($row[4] / $row[5], 3) * 100;
                    $tp = round($row[6] / $row[7], 3) * 100;
                    $ft = round($row[8] / $row[9], 3) * 100;
                    if($fg!=0.0)
                        echo "{value:$fg,formatter:'{c}%'},";
                    else
                        echo ",";
                    if($tp!=0.0)
                        echo "{value:$tp,formatter:'{c}%'},";
                    else
                        echo ",";
                    if($ft!=0.0)
                        echo "{value:$ft,formatter:'{c}%'},";
                    else
                        echo ",";
                    for ($i = 10; $i <= 17; $i++)
                        if ($row[$i] != 0.0)
                            echo "$row[$i],";
                        else
                            echo ",";
                }?>]
            }, {
                name: '常规赛',
                type: 'bar',
                label: {show: true, position: 'top'},
                data: [<?php $row = $avg_reg;
                    echo "$row[3],";
                    $fg = round($row[4] / $row[5], 3) * 100;
                    $tp = round($row[6] / $row[7], 3) * 100;
                    $ft = round($row[8] / $row[9], 3) * 100;
                    if($fg!=0.0)
                        echo "{value:$fg,formatter:'{c}%'},";
                    else
                        echo ",";
                    if($tp!=0.0)
                        echo "{value:$tp,formatter:'{c}%'},";
                    else
                        echo ",";
                    if($ft!=0.0)
                        echo "{value:$ft,formatter:'{c}%'},";
                    else
                        echo ",";
                    for ($i = 10; $i <= 17; $i++)
                        if ($row[$i] != 0.0)
                            echo "$row[$i],";
                        else
                            echo ",";
                ?>]
            }, {
                name: '季后赛',
                type: 'bar',
                label: {show: true, position: 'top'},
                data: [<?php if ($row = $avg_off->fetch_row()) {
                    echo "$row[3],";
                    $fg = round($row[4] / $row[5], 3) * 100;
                    $tp = round($row[6] / $row[7], 3) * 100;
                    $ft = round($row[8] / $row[9], 3) * 100;
                    if($fg!=0.0)
                        echo "{value:$fg,formatter:'{c}%'},";
                    else
                        echo ",";
                    if($tp!=0.0)
                        echo "{value:$tp,formatter:'{c}%'},";
                    else
                        echo ",";
                    if($ft!=0.0)
                        echo "{value:$ft,formatter:'{c}%'},";
                    else
                        echo ",";
                    for ($i = 10; $i <= 17; $i++)
                        if ($row[$i] != 0.0)
                            echo "$row[$i],";
                        else
                            echo ",";
                }?>]
            }]
        };
        myChart2.setOption(option2);
    </script>
</body>
</html>