@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'contact'])
@stop

@section('title')
	Contact
@stop

@section('body')
	<div class="col-md-6">
		<h1>Contact</h1>
		<p>The entire source code for this website is available on GitHub. To view the repository <a href="https://github.com/WesThorburn/laravel-investment-finch">click here.</a></p>
		<p>If you need to contact the administrator of investmentfinch.com.au for any reason, please email: <a href="mailto:wes@pixelfacedesigns.com.au?Subject=Investmentfinch.com.au%20Contact" target="_top">wes@pixelfacedesigns.com.au</a></p>
		<p>Alternatively, you can reach out via GitHub.</p>
	</div>
@stop