<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>NBA数据查询系统</title>
	<!-- Bootstrap -->
	<link href="css/bootstrap-4.0.0.css" rel="stylesheet">
	<link href="css/default.css" rel="stylesheet">
</head>

<body>
	<?php
	$mysqli = new mysqli( "localhost", "visitor", "1234", "basketballdb" );
	if ( $mysqli->connect_errno ) {
		printf( "Connect failed: %s\n", $mysqli->connect_error );
		exit();
	}
	$id = $_GET[ "id" ];
	if ( $id < 1 || $id > 30 )
		$id = 9;
	$result = $mysqli->query( "select * from teams where id=$id" )->fetch_array();
	?>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top"> <a class="navbar-brand" href="#"><img alt="NBA标志" src="images/nba_logo.png" style="height: 32px;width: 54px;vertical-align: top"> NBA数据库</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item"> <a class="nav-link" href="index.html">主页 </a> </li>
				<li class="nav-item active"> <a class="nav-link" href="#">球队</a> </li>

			</ul>
			<form class="form-inline my-2 my-lg-0">
				<input class="form-control mr-sm-2" type="search" placeholder="键入搜索内容" aria-label="Search">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">搜索</button>
			</form>
		</div>
	</nav>
	<div class="jumbotron jumbotron-fluid text-center">
		<br>
		<div class="container card-deck-wrapper">
			<div class="card-deck">
				<div class="card col-lg-4"> <img id="team-pic" src="images/teams/<?php echo $id;?>.png" alt="球队标志">
					<div class="card-body">
						<h3 class="card-title">
							<?php echo $result['city']," ",$result['name']?>
						</h3>
						<h5 class="card-subtitle team-info-1">
							<?php echo $result['english_city']," ",$result['english_name'];?>
						</h5>
						<p class="card-text">
							<?php echo $result['conference'],' | ',$result['division'];?>
						</p>
					</div>
				</div>
				<div class="card col-lg-4">
					<div class="card-body">
						<h4 class="card-title team-info-2">球队信息</h4>
						<p class="card-text team-info-3">
							<?php echo '成立时间：',$result['founded'],'年<br>所属地区：',$result['location'],'<br>主场馆：',$result['arena'],'<br>主教练：',$result['head_coach'];?>
						</p>
					</div>
				</div>
				<div class="card col-lg-4">
					<div class="card-body">
						<h4 class="card-title team-info-2">球队简介</h4>
						<p class="card-text team-info-4"><?php echo $result['description'];?></p>
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
	<script src="js/jquery-3.2.1.min.js"></script>

	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap-4.0.0.js"></script>
</body>
</html>