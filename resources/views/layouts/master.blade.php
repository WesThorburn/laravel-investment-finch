<!DOCTYPE html>
<html>
	<head>
		<title>
			StockWebsite | @yield('title')
		</title>
		
		<!-- CSS -->
		<link rel="stylesheet" href="/css/bootstrap.css">
		<link rel="stylesheet" href="/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="/css/dataTables.bootstrap.css">
		<link rel="stylesheet" href="/css/jasny-bootstrap.css">
		<link rel="stylesheet" href="/css/customstyles.css">
		
		<!-- Favicon -->
		<link rel="shortcut icon" type="image/png" href="/images/favicon.ico"/>

		<!--Javascript-->
		<script src="/js/jquery-2.1.4.min.js"></script>
		<script src="/js/jquery.dataTables.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/dataTables.bootstrap.js"></script>
		<script src="/js/jasny-bootstrap.js"></script>
		<script src="/js/extra.js"></script>
		@yield('head')
	</head>

	<body>
		<script type="text/javascript">
			function redirect(location){
				window.location.href = location;
			}
			setInterval(function(){
        		$('#clock').load('/servertime');
        	}, 1000); 
		</script>
		<div id="container">
			<nav class="navbar navbar-inverse no-margin-bottom">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<!-- Brand and toggle get grouped for better mobile display -->
							<div class="navbar-header">
								<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<a class="navbar-brand" href="/">Home</a>
								
							</div>
							<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
								<ul class="nav navbar-nav">
									<li><a href="/search">Screener <span class="sr-only">(current)</span></a></li>
								</ul>
								<div class="pull-right">
									<ul class="nav navbar-nav">
										<li>
											@if(Auth::check())
												<button onclick="redirect('/auth/logout')" class="btn btn-default btn-logout" id="logout">Logout</button>
											@else
												<button onclick="redirect('/auth/login')" class="btn btn-default btn-login" id="login">Log In</button>
												<button onclick="redirect('/auth/register')" class="btn btn-default btn-register" id="register">Register</button>
											@endif
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</nav>
			<nav class="navbar navbar-default">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div id="clock" class="quarter-margin-top"></div>
						</div>
					</div>
				</div>
			</nav>
			@yield('body')
		</div>
		@yield('footer')
	</body>

</html>