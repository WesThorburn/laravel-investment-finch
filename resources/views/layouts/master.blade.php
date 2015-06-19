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
		
		<!--Javascript-->
		<script src="/js/jquery-2.1.4.min.js"></script>
		<script src="/js/jquery.dataTables.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/dataTables.bootstrap.js"></script>
		<script src="/js/jasny-bootstrap.js"></script>
		<script src="/js/Chart.js"></script>
		<script src="/js/extra.js"></script>
		@yield('head')
	</head>

	<body>
		<script type="text/javascript">
			function redirect(location){
				window.location.href = location;
			}
		</script>
		<div id="container">
			<nav class="navbar navbar-inverse">
				<div class="container-fluid">
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
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
							<li><a href="/search">Screener <span class="sr-only">(current)</span></a></li>
						</ul>
						<div class="pull-right">
							<ul class="nav navbar-nav">
								<li>
									@if(Auth::check())
										<button onclick="redirect('/auth/logout')" class="btn btn-default btn-logout">Logout</button>
									@else
										<button onclick="redirect('/auth/login')" class="btn btn-default btn-login">Log In</button>
										<button onclick="redirect('/auth/register')" class="btn btn-default btn-register">Register</button>
									@endif
								</li>
							</ul>
						</div>
					</div><!-- /.navbar-collapse -->

				</div><!-- /.container-fluid -->
			</nav>
			@yield('body')
		</div>
		@yield('footer')
	</body>

</html>