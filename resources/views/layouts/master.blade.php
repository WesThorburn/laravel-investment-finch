<!DOCTYPE html>
<html>
	<head>
		<title>
			@yield('title') | Investment Finch
		</title>
		
		<meta name="description" content="Investment Finch is the only Australian Stock Market website that allows users to search for and rank stocks based on pre-defined metrics. A simple open source project built upon the Yahoo finance API."/>

		<!-- CSS -->
		<link rel="stylesheet" href="/css/all.css">
		
		<!-- Favicon -->
		<link rel="shortcut icon" type="image/png" href="/images/favicons/favicon.ico"/>

		<!--Javascript-->
		<script src="/js/jquery-2.1.4.min.js"></script>
		<script src="/js/jquery.dataTables.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/dataTables.bootstrap.js"></script>
		<script src="/js/jasny-bootstrap.js"></script>
		@yield('head')
	</head>

	<body>
		<script type="text/javascript">
			$(document).ready(
				function(){
					$('#marketStatus').load('/ajax/marketstatus');
					$('#marketChange').load('/ajax/marketchange');
				}
			);
			function redirect(location){
				window.location.href = location;
			}
			setInterval(function(){
        		$('#marketStatus').load('../ajax/marketstatus');
        	}, 15000);
        	setInterval(function(){
        		$('#marketChange').load('../ajax/marketchange');
        	}, 15000); 
		</script>
		<div class="wrapper">
			<div id="container">
				<nav class="navbar navbar-default navbar-ceiling">
					<div class="container">
						<div class="row">
							<div class="col-xs-12 col-sm-8 col-md-9">
								<a href="/" class="pull-left default-margin-right"><img src="{{asset('../images/logo.png')}}" width="75" height="75"></a>
								<h1 class="main-header">Investment Finch</h1>
							</div>
							<div class="col-xs-12 col-sm-4 col-md-3">
								<ul class="nav navbar-nav pull-right">
									<li>
										@if(Auth::check())
											<button onclick="redirect('/auth/logout')" class="btn btn-default btn-logout" id="logout">Logout</button>
											@if(Auth::user()->is_admin)
												<button onclick="redirect('/dashboard/marketCapAdjustments')" class="btn btn-default btn-logout">Admin</button>
											@endif
										@else
											<button onclick="redirect('/auth/login')" class="btn btn-default btn-login" id="login">Log In</button>
											<button onclick="redirect('/auth/register')" class="btn btn-default btn-register" id="register">Register</button>
										@endif
									</li>
								</ul>
								<h2 class="sub-header pull-right quarter-margin-top">The ASX simplified.</h2>
							</div>
						</div>
					</div>
				</nav>
				<nav class="navbar navbar-inverse no-margin-bottom">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								@yield('nav')
							</div>
						</div>
					</div>
				</nav>
				<!-- Don't display market info on homepage -->
				@if(Request::route()->uri() != '/')
					<nav class="navbar navbar-default three-quarter-margin-bottom">
						<div class="container">
							<div class="row">
								<div id="marketStatus" class="quarter-margin-top three-quarter-margin-left"></div>
								<div id="marketChange" class="quarter-margin-top three-quarter-margin-left"></div>
							</div>
						</div>
					</nav>
				@endif
				@yield('body')
			</div>
			<div class="push"></div>
		</div>
		@yield('footer')
		<div class="footer">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<p class="footer-text text-center">Wes Thorburn <a href="https://github.com/WesThorburn">(github.com/WesThorburn)</a></p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<p class="disclaimer-text">
							Investmentfinch.com.au accepts no responsibility for the accuracy or reliability of any information displayed on this website. 
							No information provided on this website should be used for investment or trading decisions, nor should information on this website be considered financial advice. Investmentfinch.com.au will not be responsible for any financial losses suffered.<br>
							Copyright © 2016 Investmentfinch.com.au. All rights reserved.
						</p>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>