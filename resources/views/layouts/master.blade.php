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
		<script src="/js/dataTables.responsive.min.js"></script>
		@yield('head')
	</head>

	<body>
		@include('layouts.partials.analytics-tracking')
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
        		$('#marketStatus').load('/ajax/marketstatus');
        		$('#marketChange').load('/ajax/marketchange');
        	}, 15000); 
		</script>
		<div class="wrapper">
			<nav class="navbar navbar-default navbar-ceiling">
				<div class="col-md-8 col-md-offset-2 no-padding-left no-padding-right">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-8 col-sm-8 col-md-8">
								<a href="/" class="pull-left default-margin-right"><img src="{{asset('../images/logo.png')}}" width="75" height="75"></a>
								<h1 class="main-header">Investment Finch</h1>
							</div>
							<div class="col-xs-4 col-sm-4 col-md-4">
								<div class="row">
									<h2 class="sub-header pull-right text-right quarter-margin-top">The ASX simplified.</h2>
								</div>
								<div class="row">
									<ul class="nav navbar-nav pull-right">
										<li>
											@if(Auth::check())
												<div class="btn-group hidden-xs">
													<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<b>{{ Auth::user()->name }}</b> <span class="caret"></span>
													</button>
													<ul class="user-main-dropdown dropdown-menu dropdown-menu-right">
														<li><a href="/user/watchlist">Watchlist</a></li>
														<li><a href="/user/portfolio">Portfolio</a></li>
														<li><a href="/user/account">Account</a></li>
														<li role="separator" class="divider"></li>
														@if(Auth::user()->is_admin)
															<li><a href="/dashboard/marketCapAdjustments">Admin</a></li>
														@endif
														<li><a href="/auth/logout">Logout</a></li>
													</ul>
												</div>
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
			<nav class="navbar navbar-inverse no-margin-bottom">
				<div class="col-md-8 col-md-offset-2 no-padding-left no-padding-right">
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-12 no-padding-left no-padding-right">
								@yield('nav')
							</div>
						</div>
					</div>
				</div>
			</nav>
			<!-- Don't display market info on homepage -->
			@if(Request::route()->uri() != '/')
				<nav class="navbar navbar-default three-quarter-margin-bottom">
					<div class="col-md-8 col-md-offset-2">
						<div class="container">
							<div class="row">
								<div id="marketStatus" class="quarter-margin-top"></div>
								<div id="marketChange" class="quarter-margin-top"></div>
							</div>
						</div>
					</div>
				</nav>
			@endif
			<div class="row">
				<div class="col-md-2 no-padding-left no-padding-right">
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
						<!-- test-responsive -->
						<ins class="adsbygoogle"
						     style="display:block"
						     data-ad-client="ca-pub-7363637623148655"
						     data-ad-slot="7086637177"
						     data-ad-format="auto"></ins>
						<script>
						(adsbygoogle = window.adsbygoogle || []).push({});
					</script>
				</div>
				<div class="col-md-8">
					@yield('body')
					<div class="push"></div>
				</div>
				<div class="col-md-2 no-padding-left no-padding-right">
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
						<!-- test-responsive -->
						<ins class="adsbygoogle"
						     style="display:block"
						     data-ad-client="ca-pub-7363637623148655"
						     data-ad-slot="7086637177"
						     data-ad-format="auto"></ins>
						<script>
						(adsbygoogle = window.adsbygoogle || []).push({});
					</script>
				</div>
			</div>
		</div>
		@yield('footer')

		<!-- Don't Display Footer on Dashboard -->
		@if(strpos(Request::route()->uri(), 'dashboard') === false)
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
							</p>
						</div>
					</div>
					<div class="row">
						<div class="col-md-1 col-md-offset-4">
							<a href="/about">About</a>
						</div>
						<div class="col-md-1">
							<a href="/privacy">Privacy</a>
						</div>
						<div class="col-md-1">
							<a href="/contact">Contact</a>
						</div>
					</div>
				</div>
			</div>
		@endif
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<script>
		  (adsbygoogle = window.adsbygoogle || []).push({
		    google_ad_client: "ca-pub-7363637623148655",
		    enable_page_level_ads: true
		  });
		</script>
	</body>
</html>