<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>球队资料</title>
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
	$id = $_GET[ "id" ];
	if ( $id < 1 || $id > 30 )
		$id = 9;
	$result = $mysqli->query( "select * from teams where id=$id" )->fetch_array();
	$team_name = $result[ 'name' ];
	$coach_name = $result[ 'head_coach' ];
	$players = $mysqli->query( "select number,id,name,position,height,weight,TIMESTAMPDIFF(YEAR, born, CURDATE()) as age,draft_year,salary from players where team_name='$team_name' order by salary desc" );
	$coach_id = $mysqli->query( "select id from coaches where name='$coach_name'" )->fetch_row()[ 0 ];
	?>
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
							<?php echo '成立时间：', $result['founded'], '年<br>所属地区：', $result['location'], '<br>主场馆：', $result['arena'], "<br>主教练：<a href='coach.php?id=$coach_id'>", $result['head_coach'],'</a>'; ?>
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
			<br>
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
							while ( $row = $players->fetch_row() ) {
								echo "<tr>";
								echo "<td>", $row[ 0 ], '</td>';
								echo "<td>", "<img class='img-thumbnail' alt='头像' src='images/player/$row[1].png'></td>";
								echo "<td>", '<a href="player.php?id=', $row[ 1 ], '">', $row[ 2 ], '</a></td>';
								for ( $i = 3; $i <= 8; $i++ ) {
									if ( $row[ $i ] == Null )
										$row[ $i ] = "-";
									echo "<td>", $row[ $i ], "</td>";
								}
								echo "</tr>";
							}
							?>
						</table>
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
					</a> <a target="_blank" href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=37028202000232"><img
                            src="images/beian.png" style="float:left;" alt="公安部标志"/>
                    <p>鲁公网安备 37028202000232号</p>
                </a>
				</div>
			</div>
		</div>
	</div>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>

	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="https://cdn.bootcss.com/popper.js/1.14.3/popper.min.js"></script>
	<script src="https://cdn.bootcss.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</body>
</html>