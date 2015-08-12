<!DOCTYPE html>
<html>
	<head>
		<title>
			@yield('title') | ASX Stocks | Wes Thorburn
		</title>
		
		<meta name="description" content="The only Australian Stock Market website that allows users to search for and rank stocks based on pre-defined metrics. A simple open source project built upon the Yahoo finance API."/>

		<!-- CSS -->
		<link rel="stylesheet" href="/css/bootstrap.css">
		<link rel="stylesheet" href="/css/bootstrap-theme.css">
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
							<div class="pull-right half-margin-top">
								{!! Form::open(['action' => 'StockController@show', 'method' => 'get', 'class' => 'form-group form-inline']) !!}
									{!! Form::text('stockCode', null, ['class' => 'form-control', 'placeholder' => 'Stock Code']) !!}
									{!! Form::submit("Search", ['class' => 'btn btn-default form-control']) !!}
								{!! Form::close() !!}
							</div>
						</div>
					</div>
				</div>
			</nav>
			@yield('body')
		</div>
		@yield('footer')
	</body>

</html>