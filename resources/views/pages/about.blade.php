@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'about'])
@stop

@section('title')
	About
@stop

@section('body')
	<div class="col-md-6">
		<h1>About</h1>
		<p>Investment finch was created by Wes Thorburn, a Brisbane-based PHP Web developer. Here is an exerpt from the repository's readme found on GitHub.</p>

		<i><p>This is an ongoing solo-project I have been working on that allows users to search for and view stocks listed on the Australian Stock Exchange (ASX). My goal for this project is to gain experience working with very large data sets as well as some experience working with automation within Laravel.</p>

		<p>At the moment, there are a little over 3000 stocks on the ASX. I used the Yahoo Finance API to locate historical trading data dating back as far as early 2000. The database table for the historical financials has over 6 million rows, and grows by more than 3,000 each trading day.</p>

		<p>At regular intervals throughout each trading day, stocks' metrics are downloaded, processed and saved. Sector performance and stock interval gains/losses are calculated at the end of each trading day. Shortly after midnight each night, another task is executed which downloads the latest list of ASX listed companies from the ASX servers. Trend data for each stock is also calculated during this time.</p>

		<p>From the website, users can view the best/worst performing stocks and sectors. Clicking on individual stocks displays information about the company, a historical price graph as well as some related stocks. With the use of indexes in MySQL, querying a table containing over 6 Million rows takes only a fraction of a second.</p>

		<p>I'm always looking for ways to improve this project, so if have any suggestions, feel free to drop me a line.</p></i>

		<p>The entire source code for this website is available on GitHub. To view the repository <a href="https://github.com/WesThorburn/laravel-investment-finch">click here.</a></p>

		<p>For contact information <a href="/about">click here.</a></p>

		<p>For privacy information <a href="/privacy">click here.</a></p>
	</div>
@stop