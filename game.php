<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>赛程信息</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/default.css" rel="stylesheet">
    <link href="images/ico.ico" rel="shortcut icon">
</head>

<body>
<?php
$mysqli = new mysqli("localhost", "visitor", "1234", "basketballdb");
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}
$id = $_GET['id'];
$result = $mysqli->query("select * from game_info where id=$id")->fetch_array();
$away_name = $result['away'];
$home_name = $result['home'];
$away = $mysqli->query("select id,city from teams where name='$away_name'")->fetch_row();
$home = $mysqli->query("select id,city,arena from teams where name='$home_name'")->fetch_row();
$away_player = $mysqli->query("SELECT
	* 
FROM
	( SELECT id AS player_id, name FROM players ) AS players
	NATURAL JOIN player_record 
WHERE
	game_id = $id 
	AND away_home = 0 
ORDER BY
	gs DESC,
	time DESC");
$home_player = $mysqli->query("SELECT
	* 
FROM
	( SELECT id AS player_id, name FROM players ) AS players
	NATURAL JOIN player_record 
WHERE
	game_id = $id 
	AND away_home = 1 
ORDER BY
	gs DESC,
	time DESC");
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
<br>
<br>
<br>
<div class="container">
    <div class="card bg-light">
        <div class="card-header text-info"><?php echo "开始时间：$result[1]";
        if($result[1]<'2017-10-18')
            echo "（季前赛）";
        else if($result[1]<'2018-04-18')
            echo "（常规赛）";
        else
            echo "（季后赛）";
        echo "&nbsp;&nbsp;$result[2]&nbsp;&nbsp;&nbsp;&nbsp;时长：", date_format(date_create($result[7]), "G时i分"), "&nbsp;&nbsp;&nbsp;&nbsp;球馆：$home[2]&nbsp;&nbsp;&nbsp;&nbsp;上座人数：$result[8]人" ?> </div>
        <div class="row">
            <div class="col" id="game-away">
                <a href="team.php?id=<?php echo $away[0] ?>">
                    <img class="float-left" src="images/team/<?php echo $away[0] ?>.png" height="100px">
                    <h2><?php echo $away[1], $away_name ?></h2>
                    <p>客场：<span class="text-danger"><?php echo $result['away_score'] ?></span>分</p>
                </a>

            </div>
            <div class="col" id="game-mid">

                    <table class="table-sm table-bordered text-center">
                        <tr>
                            <th>球队</th>
                            <th>第1节</th>
                            <th>第2节</th>
                            <th>第3节</th>
                            <th>第4节</th>
                        </tr>
                        <tr><?php echo "<th>$away_name</th>";
                            for ($i = 9; $i <= 12; $i++) {
                                echo "<td>$result[$i]</td>";
                            } ?></tr>
                        <tr><?php echo "<th>$home_name</th>";
                            for ($i = 13; $i <= 16; $i++) {
                                echo "<td>$result[$i]</td>";
                            } ?></tr>
                    </table>

            </div>
            <div class="col" id="game-home">
                <a href="team.php?id=<?php echo $home[0] ?>">
                    <img class="float-right" src="images/team/<?php echo $home[0] ?>.png" height="100px">
                    <h2><?php echo $home[1], $home_name ?></h2>
                    <p>主场：<span class="text-danger"><?php echo $result['home_score'] ?></span>分</p>
                </a>

            </div>
        </div>

    </div>

</div>
<div class="container">
    <div class="card bg-light">
        <div id="data-compare" style="height: 500px;width:100% ">

        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="card">

        <div class="card-body">
            <h3 class="text-secondary">
                <img alt="球队标志" height="50px"
                     src="images/team/<?php echo $away[0] ?>.png"><?php echo $away[1], $away_name ?>
            </h3>
            <div class="dropdown-divider"></div>
            <table class="table-sm table-bordered table-hover text-center">
                <tr class="table-secondary">
                    <th>首发球员</th>
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
                <?php
                for ($i = 1; $i <= 5; $i++) {
                    $r = $away_player->fetch_row();
                    echo "<tr>";
                    echo "<td><a href='player.php?id=$r[0]'>$r[1]</a></td>";
                    echo "<td>$r[5]</td>";
                    for ($j = 6; $j <= 11;) {
                        $a = $r[$j++];
                        $b = $r[$j++];
                        echo "<td>$a-$b</td>";
                        if ($b == 0)
                            $c = '-';
                        else
                            $c = round($a / $b, 3) * 100 . '%';
                        echo "<td>$c</td>";
                    }
                    for ($k = 12; $k <= 19; $k++)
                        echo "<td>$r[$k]</td>";
                    if ($r[20] > 0)
                        echo "<td>+$r[20]</td>";
                    else
                        echo "<td>$r[20]</td>";
                    echo "</tr>";
                    echo "</tr>";
                }
                ?>
                <tr class="table-secondary">
                    <th>替补球员</th>
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
                <?php
                for ($i = 6; $r = $away_player->fetch_row(); $i++) {
                    echo "<tr>";
                    echo "<td><a href='player.php?id=$r[0]'>$r[1]</a></td>";
                    echo "<td>$r[5]</td>";
                    for ($j = 6; $j <= 11;) {
                        $a = $r[$j++];
                        $b = $r[$j++];
                        echo "<td>$a-$b</td>";
                        if ($b == 0)
                            $c = '-';
                        else
                            $c = round($a / $b, 3) * 100 . '%';
                        echo "<td>$c</td>";
                    }
                    for ($k = 12; $k <= 19; $k++)
                        echo "<td>$r[$k]</td>";
                    if ($r[20] > 0)
                        echo "<td>+$r[20]</td>";
                    else
                        echo "<td>$r[20]</td>";
                    echo "</tr>";
                    echo "</tr>";
                }
                ?>
                <tr class="table-info">
                    <th>全队</th>
                    <td></td>
                    <?php
                    for ($i = 17; $i <= 22;){
                        $a = $result[$i++];
                        $b = $result[$i++];
                        echo "<td>$a-$b</td>";
                        if ($b == 0)
                            $c = '-';
                        else
                            $c = round($a / $b, 3) * 100 . '%';
                        echo "<td>$c</td>";
                    }
                    for ($i = 23; $i <= 29; $i++)
                        echo "<td>$result[$i]</td>";
                    echo "<td>$result[6]</td>";
                    ?>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="card">

        <div class="card-body">
            <h3 class="text-secondary">
                <img alt="球队标志" height="50px"
                     src="images/team/<?php echo $home[0] ?>.png"><?php echo $home[1], $home_name ?>
            </h3>
            <div class="dropdown-divider"></div>
            <table class="table-sm table-bordered table-hover text-center">
                <tr class="table-secondary">
                    <th>首发球员</th>
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
                <?php
                for ($i = 1; $i <= 5; $i++) {
                    $r = $home_player->fetch_row();
                    echo "<tr>";
                    echo "<td><a href='player.php?id=$r[0]'>$r[1]</a></td>";
                    echo "<td>$r[5]</td>";
                    for ($j = 6; $j <= 11;) {
                        $a = $r[$j++];
                        $b = $r[$j++];
                        echo "<td>$a-$b</td>";
                        if ($b == 0)
                            $c = '-';
                        else
                            $c = round($a / $b, 3) * 100 . '%';
                        echo "<td>$c</td>";
                    }
                    for ($k = 12; $k <= 19; $k++)
                        echo "<td>$r[$k]</td>";
                    if ($r[20] > 0)
                        echo "<td>+$r[20]</td>";
                    else
                        echo "<td>$r[20]</td>";
                    echo "</tr>";
                    echo "</tr>";
                }
                ?>
                <tr class="table-secondary">
                    <th>替补球员</th>
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
                <?php
                for ($i = 6; $r = $home_player->fetch_row(); $i++) {
                    echo "<tr>";
                    echo "<td><a href='player.php?id=$r[0]'>$r[1]</a></td>";
                    echo "<td>$r[5]</td>";
                    for ($j = 6; $j <= 11;) {
                        $a = $r[$j++];
                        $b = $r[$j++];
                        echo "<td>$a-$b</td>";
                        if ($b == 0)
                            $c = '-';
                        else
                            $c = round($a / $b, 3) * 100 . '%';
                        echo "<td>$c</td>";
                    }
                    for ($k = 12; $k <= 19; $k++)
                        echo "<td>$r[$k]</td>";
                    if ($r[20] > 0)
                        echo "<td>+$r[20]</td>";
                    else
                        echo "<td>$r[20]</td>";
                    echo "</tr>";
                    echo "</tr>";
                }
                ?>
                <tr class="table-info">
                    <th>全队</th>
                    <td></td>
                    <?php
                    for ($i = 30; $i <= 35;){
                        $a = $result[$i++];
                        $b = $result[$i++];
                        echo "<td>$a-$b</td>";
                        if ($b == 0)
                            $c = '-';
                        else
                            $c = round($a / $b, 3) * 100 . '%';
                        echo "<td>$c</td>";
                    }
                    for ($i = 36; $i <= 42; $i++)
                        echo "<td>$result[$i]</td>";
                    echo "<td>$result[5]</td>";
                    ?>
                    <td></td>
                </tr>
            </table>
        </div>
        <div class="card-footer text-secondary">*注意：部分在赛季中途退出联盟的球员数据可能显示不全</div>
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
<script src="https://cdn.bootcss.com/echarts/4.1.0.rc2/echarts.min.js"></script>
<script>
    var myChart=echarts.init($('#data-compare')[0])
    var option = {
        title: {
            text: '两队数据对比'
        },
        tooltip : {
            trigger: 'axis',
            axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
            }
        },
        legend: {
            data:[<?php echo "'$away_name','$home_name'"?>]
        },
        grid: [{
            left:'5%',
            right:'53%',
            top:'5%',
            bottom:'0%'
        },{
            left:'53%',
            right:'5%',
            top:'5%',
            bottom:'0%'
        }],
        xAxis:[{
            show:false,
            type : 'log',
            inverse:true,

        },{
            show:false,
            type : 'log',
            gridIndex:1
        }],
        yAxis: [{
            type : 'category',
            inverse:true,
            show:false,
            data : ['投篮', '投篮%', '三分', '三分%', '罚球', '罚球%', '进攻篮板', '防守篮板', '助攻', '犯规', '抢断', '失误', '盖帽'],
        },{
            type : 'category',
            inverse:true,
            axisLine:{show:false},
            axisTick:{show:false},
            axisLabel:{align:'center',margin:32},
            data : ['投篮', '投篮%', '三分', '三分%', '罚球', '罚球%', '进攻篮板', '防守篮板', '助攻', '犯规', '抢断', '失误', '盖帽'],
            gridIndex:1
        }],
        series : [
            {
                name:'<?php echo $away_name?>',
                type:'bar',
                label: {
                        show: true,
                        position:'left'
                },
                data:[<?php echo "$result[17],{value:",round($result[17]/$result[18],3)*100,",label:{formatter:'{c}%'}},$result[19],{value:",round($result[19]/$result[20],3)*100,",label:{formatter:'{c}%'}},$result[21],{value:",round($result[21]/$result[22],3)*100,",label:{formatter:'{c}%'}}";for($i=23;$i<=29;$i++)echo ",$result[$i]";?>],
            },{
                name:'<?php echo $home_name?>',
                type:'bar',
                label: {
                        show: true,
                        position:'right',
                },

                data:[<?php echo "$result[30],{value:",round($result[30]/$result[31],3)*100,",label:{formatter:'{c}%'}},$result[32],{value:",round($result[32]/$result[33],3)*100,",label:{formatter:'{c}%'}},$result[34],{value:",round($result[34]/$result[35],3)*100,",label:{formatter:'{c}%'}}";for($i=36;$i<=42;$i++)echo ",$result[$i]";?>],
                xAxisIndex: 1,
                yAxisIndex: 1,
            }
        ]
    };
    myChart.setOption(option)
</script>
</body>
</html>