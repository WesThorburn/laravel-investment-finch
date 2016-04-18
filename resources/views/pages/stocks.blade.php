@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'stocks'])
@stop

@section('title')
	Stocks
@stop

@section('body')
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div id="metrics">
					@include('layouts.partials.stock-list-display')
				</div>
			</div>
		</div>
	</div>
@stop