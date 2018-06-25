<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>搜索结果</title>
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
$default = ['name' => '未选择球员', 'english_name' => '请在球员页选择要比对的球员', 'number' => '#', 'position' => '位置', 'team_name' => '球队'];
if (isset($_COOKIE["player1_id"])) {
    $player1_id = $_COOKIE["player1_id"];
    $player1 = $mysqli->query("SELECT * FROM player_avg WHERE player_id=$player1_id")->fetch_array();
} else {
    $player1_id = 'default';
    $player1 = $default;
}
if (isset($_COOKIE["player2_id"])) {
    $player2_id = $_COOKIE["player2_id"];
    $player2 = $mysqli->query("SELECT * FROM player_avg WHERE player_id=$player2_id")->fetch_array();
} else {
    $player2_id = 'default';
    $player2 = $default;
}

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
<div class="jumbotron jumbotron-fluid">
<div class="container card-deck-wrapper">
    <div class="card-deck">
        <div id="compare-player1" class="card col-4 text-center">
            <div class="card-body"><img id="player-pic" src="images/player_big/<?php echo $player1_id; ?>.png"
                                        alt="球员头像">

                <h3 class="card-title">
                    <?php if ($player1_id != 'default') echo "<a href='player.php?id=$player1_id'>", $player1['name'], "</a>"; else echo $player1['name'] ?>
                </h3>
                <h5 class="card-subtitle team-info-1">
                    <?php echo $player1['english_name']; ?>
                </h5>
                <h4>
                    <small>
                        <?php if ($player1_id != 'default') echo '<a href="team.php?id=', $player1['team_id'], '">', $player1['city'], $player1['team_name'], '队球员</a>'; else echo $player1['team_name'] ?>
                    </small>
                </h4>
                <p class="lead">
                    <?php echo $player1['number'], '号 | ', $player1['position'] ?>
                </p>
            </div>
        </div>
        <div id="compare-radar" class="card col-4" style="height:403.4px;width:100%">
        </div>
        <div id="compare-player2" class="card col-4 text-center">
            <div class="card-body"><img id="player-pic" src="images/player_big/<?php echo $player2_id; ?>.png"
                                        alt="球员头像">

                <h3 class="card-title">
                    <?php if ($player2_id != 'default') echo "<a href='player.php?id=$player2_id'>", $player2['name'], "</a>"; else echo $player2['name'] ?>
                </h3>
                <h5 class="card-subtitle team-info-1">
                    <?php echo $player2['english_name']; ?>
                </h5>
                <h4>
                    <small>
                        <?php if ($player2_id != 'default') echo '<a href="team.php?id=', $player2['team_id'], '">', $player2['city'], $player2['team_name'], '队球员</a>'; else echo $player2['team_name'] ?>
                    </small>
                </h4>
                <p class="lead">
                    <?php echo $player2['number'], '号 | ', $player2['position'] ?>
                </p>
            </div>
        </div>
    </div>
    <br>
    <div id="compare-bar" class="card" style="height: 600px;width: 100%"></div>
</div>
</div>>
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
    $(document).ready(function () {
        if ('<?php echo $player1_id?>' == 'default' || '<?php echo $player2_id?>' == 'default')
            return;
        var radar = echarts.init($('#compare-radar')[0]);
        var option_radar = {

            tooltip: {},
            legend: {
                data: ['<?php echo $player1['name'] ?>', '<?php echo $player2['name'] ?>']
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
                indicator: [{
                    name: '场均得分',
                }, {
                    name: '场均篮板',
                }, {
                    name: '场均助攻',
                }, {
                    name: '场均抢断',
                }, {
                    name: '场均盖帽',
                },]
            },
            series: [{
                name: '数据统计',
                type: 'radar',
                data: [{
                    value: [<?php for ($i = 16; $i <= 20; $i++) echo "$player1[$i]," ?>],
                    name: '<?php echo $player1['name'] ?>',
                    label: {
                        show: true
                    },
                    areaStyle: {}
                }, {
                    value: [<?php for ($i = 16; $i <= 20; $i++) echo "$player2[$i]," ?>],
                    name: '<?php echo $player2['name'] ?>',
                    label: {
                        show: true
                    },
                    areaStyle: {}

                },

                ]
            }]
        };
        radar.setOption(option_radar);
        var bar=echarts.init($('#compare-bar')[0]);
        var option_bar = {
            title: {
                text: '两球员数据对比'
            },
            tooltip : {
                trigger: 'axis',
                axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                    type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                }
            },
            legend: {
                data:['<?php echo $player1['name'] ?>', '<?php echo $player2['name'] ?>']
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
                data : ['首发','时间','投篮', '投篮%', '三分', '三分%', '罚球', '罚球%', '得分','篮板', '助攻',  '抢断', '盖帽','失误', '犯规'],
            },{
                type : 'category',
                inverse:true,
                axisLine:{show:false},
                axisTick:{show:false},
                axisLabel:{align:'center',margin:32},
                data : ['首发','时间','投篮', '投篮%', '三分', '三分%', '罚球', '罚球%', '得分','篮板', '助攻',  '抢断', '盖帽','失误', '犯规'],
                gridIndex:1
            }],
            series : [
                {
                    name:'<?php echo $player1['name']?>',
                    type:'bar',
                    label: {
                        show: true,
                        position:'left'
                    },
                    data:[<?php for($i=8;$i<=22;$i++)echo "$player1[$i],"?>],
                },{
                    name:'<?php echo $player2['name']?>',
                    type:'bar',
                    label: {
                        show: true,
                        position:'right',
                    },

                    data:[<?php for($i=8;$i<=22;$i++)echo "$player1[$i],"?>],
                    xAxisIndex: 1,
                    yAxisIndex: 1,
                }
            ]
        };
        bar.setOption(option_bar);
    })

</script>
</body>
</html>