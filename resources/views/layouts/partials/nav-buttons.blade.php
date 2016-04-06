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
	<ul class="nav navbar-nav nav-font">
		<li class="nav-button @if($page == 'home') active @endif"><a href="/">Home</a></li>
		<li class="nav-button @if($page == 'sectors') active @endif"><a href="/sectors">Sectors</a></li>
		<li class="nav-button @if($page == 'stocks') active @endif"><a href="/index/all">Stocks</a></li>
		<li class="nav-button @if($page == 'performance') active @endif"><a href="/performance">Performance</a></li>
		@if(Auth::check())
			<li class="nav-button @if($page == 'watchlist') active @endif"><a href="/user/watchlist">Watchlist</a></li>
			<li class="nav-button @if($page == 'portfolio') active @endif"><a href="/user/portfolio">Portfolio</a></li>
			<li class="nav-button @if($page == 'account') active @endif"><a href="/user/account">Account</a></li>
		@endif
	</ul>
	<div class="pull-right">
		<ul class="nav navbar-nav">
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
	</div>
</div>