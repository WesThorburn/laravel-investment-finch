<!DOCTYPE html>
<html>
	<head>
		<title>
			@yield('title') | ASX Stocks | Wes Thorburn
		</title>
		
		<meta name="description" content="The only Australian Stock Market website that allows users to search for and rank stocks based on pre-defined metrics. A simple open source project built upon the Yahoo finance API."/>

		<!-- CSS -->
		<link rel="stylesheet" href="/css/all.css">
		
		<!-- Favicon -->
		<link rel="shortcut icon" type="image/png" href="/images/favicon.ico"/>

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
        		$('#marketStatus').load('/ajax/marketstatus');
        	}, 15000);
        	setInterval(function(){
        		$('#marketChange').load('/ajax/marketchange');
        	}, 15000); 
		</script>
		<div id="container">
			<nav class="navbar navbar-default navbar-ceiling">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<a href="/" class="pull-left default-margin-right"><img src="../images/logo.png" width="75" height="75"></a>
							<h1 class="main-header">Investment Finch</h1>
						</div>
						<div class="col-md-6">
							<h2 class="sub-header pull-right double-margin-top">The ASX simplified.</h2>
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
			<nav class="navbar navbar-default">
				<div class="container">
					<div class="row">
						<div class="col-xs-6">
							<div id="marketStatus" class="quarter-margin-top"></div>
							<div id="marketChange" class="quarter-margin-top"></div>
						</div>
						<div class="col-xs-6">
							@include('layouts.partials.search-box')
						</div>
					</div>
				</div>
			</nav>
			@yield('body')
		</div>
		@yield('footer')
	</body>

</html>