<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>赛程信息</title>
	<!-- Bootstrap -->
	<link href="https://cdn.bootcss.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
	<link href="css/default.css" rel="stylesheet">
	<link href="images/ico.ico" rel="shortcut icon">
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
</body>
</html>