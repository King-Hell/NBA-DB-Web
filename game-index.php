<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>赛程列表</title>
	<!-- Bootstrap -->
	<link href="https://cdn.bootcss.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
	<link href="css/default.css" rel="stylesheet">
	<link href="images/ico.ico" rel="shortcut icon">
	<script src="https://cdn.bootcss.com/moment.js/2.22.1/moment-with-locales.min.js"></script>
	<script src="https://cdn.bootcss.com/moment.js/2.22.1/locale/zh-cn.js"></script>
	<link rel="stylesheet" href="css/pikaday.css">
	<script src="js/pikaday.js"></script>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top"> <a class="navbar-brand" href="#"><img alt="NBA标志" src="images/nba_logo.png" style="height: 32px;width: 54px;vertical-align: top"> NBA数据库</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active"> <a class="nav-link" href="index.html">主页 </a>
				</li>
				<li class="nav-item"> <a class="nav-link" href="team-index.html">球队</a>
				</li>
				<li class="nav-item"> <a class="nav-link" href="player-index.php">球员</a>
				</li>
				<li class="nav-item"> <a class="nav-link" href="coach-index.php">教练</a>
				</li>
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
	<br>
	<div class="jumbotron jumbotron-fluid text-center">
		<div class="container">
			<div class="card text-center">
				<div class="card-header">
					<h2 class="text-info">赛程查询</h2>
					<div class="input-group col-4 offset-4">
						<span class="input-group-text">当前日期</span>
						<input type="text" class="form-control" id="calendar">
						<span class="input-group-btn">
        				<button class="btn btn-success" type="button" id="btn-ser">查询</button>
      					</span>
					



					</div>



				</div>
				<div class="card-body">
					<table class="table table-hover table-players">
						<thead>
							<tr>
								<th>客队</th>
								<th>客队比分</th>
								<th>详情</th>
								<th>主队比分</th>
								<th>主队</th>
							</tr>
						</thead>
					</table>
				</div>
				<div class="card-footer text-muted" id="card-footer"></div>
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
					</a> <a target="_blank" href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=37028202000232"><img src="images/beian.png" style="float:left;" alt="公安部标志"/>
        <p>鲁公网安备 37028202000232号</p>
        </a> </div>
			</div>
		</div>
	</div>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>

	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="https://cdn.bootcss.com/popper.js/1.14.3/popper.min.js"></script>
	<script src="https://cdn.bootcss.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script>
		var picker = new Pikaday( {
			field: $( '#calendar' )[ 0 ],
			minDate: new Date( '2017-10-18' ),
			maxDate: new Date( '2018-06-09' ),
			defaultDate: new Date( '2018-6-9' ),
			setDefaultDate: true,
			onSelect: function () {

			}
		} );
		$( '#btn-ser' ).click( function () {
			$.get( 'query.php', {
				type: 'game_list',
				date: $( '#calendar' ).val()
			}, function ( response, status, xhr ) {
				$( '#card-footer' ).text( '该日有' + $( response ).find( 'game' ).length + '场比赛' )
				$( response ).find( 'game' ).each( function () {
					var away_id = $( this ).children( 'away_id' ).text()
					var home_id = $( this ).children( 'home_id' ).text()
					var away = $( this ).children( 'away' ).text()
					var home = $( this ).children( 'home' ).text()
					var away_score = $( this ).children( 'away_score' ).text()
					var home_score = $( this ).children( 'home_score' ).text()
					var id = $( this ).children( 'id' ).text()
					var text = '<tr><td><a href="team.php?id=' + away_id + '"><img src="images/team/' + away_id + '.png">' + away + '</a></td>' +
						'<td>' + away_score + '</td>' +
						'<td><a href="game.php?id=' + id + '">数据统计</a></td>' +
						'<td>' + home_score + '</td>' +
						'<td><a href="team.php?id=' + home_id + '"><img src="images/team/' + home_id + '.png">' + home + '</a></td></tr>'
					$( '.table' ).append( text )

				} )

			}, 'xml' )
		} )
	</script>

</body>
</html>