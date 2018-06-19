<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>球员资料</title>
	<!-- Bootstrap -->
	<link href="https://cdn.bootcss.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
	<link href="css/default.css" rel="stylesheet">
	<link href="images/ico.ico" rel="shortcut icon">
</head>

<body>
	<?php
	$mysqli = new mysqli( "localhost", "visitor", "1234", "basketballdb" );
	if ( $mysqli->connect_errno ) {
		printf( "Connect failed: %s\n", $mysqli->connect_error );
		exit();
	}
	if ( !isset( $_GET[ "id" ] ) ) {
		$id =3311;
	} else {
		$id = $_GET[ "id" ];
	}
	$result = $mysqli->query( "select * from players where id=$id" )->fetch_assoc();
	$team_name=$result['team_name'];
	$team = $mysqli->query( "select id,city from teams where name='$team_name'" )->fetch_row();
	?>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top"> <a class="navbar-brand" href="#"><img alt="NBA标志" src="images/nba_logo.png" style="height: 32px;width: 54px;vertical-align: top"> NBA数据库</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active"> <a class="nav-link" href="index.html">主页 </a></li>
      <li class="nav-item"> <a class="nav-link" href="team-index.html">球队</a></li>
      <li class="nav-item"> <a class="nav-link" href="player-index.php">球员</a></li>
      <li class="nav-item"> <a class="nav-link" href="coach-index.php">教练</a></li>
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
					<img id="player-pic" src="images/player_big/<?php echo $result['id']; ?>.png" alt="球员头像" onerror="src='images/player_big/default.png'">
					<div class="card-body">
						<h3 class="card-title">
							<?php echo $result['name'] ?>
						</h3>
						<h5 class="card-subtitle team-info-1">
							<?php echo $result['english_name']; ?>
						</h5>
						<h4><small>
							<?php echo '<a href="team.php?id=',$team[0],'">',$team[1],$result['team_name'],'队球员</a>'; ?>
						</small></h4>
						<p class="lead">
							<?php echo $result['number'],'号 | ',$result['position']?>
							</p>
					

					</div>
				</div>
				<div class="card col-lg-4">
					<div class="card-body">
						<h4 class="card-title team-info-2">个人信息</h4>
						<p class="card-text team-info-3">
							<?php echo '身高：', $result['height'], 'cm<br>体重：', $result['weight'], 'kg<br>出生日期：', $result['born'], '<br>学校：',$result['school'], '<br>国籍：',$result['nationality']; ?>
						</p>
					</div>
				</div>
				<div class="card col-lg-4">
					<div class="card-body">
						<h4 class="card-title team-info-2">职业生涯</h4>
						<p class="card-text team-info-3">
							<?php echo '选秀：',$result['draft'],'<br>本赛季薪资：',$result['salary'],'万美元<br>合同：',$result['contract']; ?>
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
	<script src="https://cdn.bootcss.com/popper.js/1.14.3/popper.min.js"></script>
	<script src="https://cdn.bootcss.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</body>
</html>