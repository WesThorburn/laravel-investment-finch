<!-- Brand and toggle get grouped for better mobile display -->
<div class="navbar-header">
	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</button>
</div>
<div class="collapse navbar-collapse" id="main-navbar-collapse">
	<ul class="nav navbar-nav">
		<li class="nav-button @if($page == 'home') active @endif"><a href="/">Home</a></li>
		<li class="nav-button @if($page == 'sectors') active @endif"><a href="/sectors">Sectors</a></li>
		<li class="nav-button @if($page == 'stocks') active @endif"><a href="/stocks">Stocks</a></li>
		<li class="nav-button @if($page == 'topGainsLosses') active @endif"><a href="/topGainsLosses">Gains/Losses</a></li>
		<li class="nav-button @if($page == 'search') active @endif"><a href="/search">Search <span class="sr-only">(current)</span></a></li>
	</ul>
	<!--
	Removed until login functionality is added
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
	</div>-->
</div>