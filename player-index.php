<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>球员列表</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/default.css" rel="stylesheet">
    <link href="images/ico.ico" rel="shortcut icon">
</head>

<body>
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
            <li class="nav-item"><a class="nav-link" href="game-index.php">赛程</a></li>
        <li class="nav-item"> <a class="nav-link" href="compare.php">对比</a></li></ul>
        <form class="form-inline" action="search.php" target="_blank">
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

    <div class="container bg-white">
        <h2>球员索引表</h2>
        <small>按姓氏排序</small>
        <ul id="tab-index" class="nav bg-light nav-pills" role="tablist">
            <?php
            echo "<li class='nav-item'><a class='nav-link active' href='#pane-A' id='index-A' data-toggle='tab'>A</a></li>";
            for ($i = 1; $i < 26; $i++) {
                $ch = chr($i + 65);
                echo "<li class='nav-item'><a class='nav-link' href='#pane-$ch' data-toggle='tab'>$ch</a></li>";
            }
            ?>
        </ul>
        <!-- Content Panel -->
        <div id="tab-content" class="tab-content">

            <div class="tab-pane fade active show" id="pane-A">
                <p>加载中……</p>
            </div>
            <?php
            for ($i = 1; $i < 26; $i++) {
                $ch = chr($i + 65);
                echo "<div class='tab-pane fade' id='pane-$ch'><p>加载中……</p></div>";
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
<script type="text/javascript">
    $(function () {
        $('#index-A').click()
    });

    $('#tab-index a').click(function () {
        var ch = $(this).text();
        $.get('query.php', {
                type: 'player_list',
                index: ch
            },
            function (response, status, xhr) {
                $('#pane-' + ch).html("<table class='table table-players'>\
					<thead class='thead-light'>\
						<tr>\
							<th>头像</th>\
							<th>姓名</th>\
							<th>英文名</th>\
							<th>球队</th>\
						</tr>\
					</thead>\
				</table>");
                $(response).find('player').each(function () {
                    var tab = $('#pane-' + ch + ' table');
                    var id = $(this).find('id').text();
                    var text = "<tr>\
						<td><image class='img-thumbnail' alt='球员头像' src='images/player/" + $(this).find('id').text() + ".png'></td>\
						<td><a target='_blank' href='player.php?id=" + id + "'>" + $(this).find('name').text() + "</a></td>\
						<td><a target='_blank' href='player.php?id=" + id + "'>" + $(this).find('english_name').text() + "</a></td>\
						<td><a target='_blank' href='team.php?id=" + $(this).find('team_id').text() + "'>" + $(this).find('team_name').text() + "</a></td>\</tr>";
                    $('#pane-' + ch + ' table').append(text)

                })
            }, 'xml')

    })
</script>
</body>
</html>