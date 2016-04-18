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
		<li class="nav-button visible-xs @if($page == 'watchlist') active @endif"><a href="/user/watchlist">Watchlist</a></li>
		<li class="nav-button visible-xs @if($page == 'portfolio') active @endif"><a href="/user/portfolio">Portfolio</a></li>
		<li class="nav-button visible-xs @if($page == 'account') active @endif"><a href="/user/account">Account</a></li>
		@if(Auth::check() && Auth::user()->is_admin)
			<li class="nav-button visible-xs"><a href="/dashboard/marketCapAdjustments">Admin</a></li>
		@endif
		<li class="nav-button visible-xs"><a href="/auth/logout">Logout</a></li>
	</ul>
	@include('layouts.partials.search-box')
</div>