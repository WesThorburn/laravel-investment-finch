@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'stocks'])
@stop

@section('title')
	Stocks
@stop

@section('body')
	<script>
		/*$(document).ready(
            function() {
                setInterval(function() {
                	if(window.location.search == ""){
                		$('#metrics').load('/search/%7Bsearch%7D?viewType=partial');
                	}
                	else{
						$('#metrics').load('/search/%7Bsearch%7D'+window.location.search+'&viewType=partial');
                	}
                }, 60000);
            });*/
	</script>
	<div class="container">
		<!-- <div class="row">
			<div class="col-sm-12">
				<div class="pull-left">
					{!! Form::open(['action' => 'SearchController@show', 'method' => 'get', 'class' => 'form-group form-inline']) !!}
						{!! Form::select('stockSector', $stockSectors, $stockSectorName, ['class' => 'form-control']) !!}
						{!! Form::submit("Filter Sector", ['class' => 'btn btn-default form-control']) !!}
					{!! Form::close() !!}
				</div>
			</div>
		</div> -->
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="#">All Stocks</a></li>
			<li role="presentation"><a href="#">ASX 20</a></li>
			<li role="presentation"><a href="#">ASX 50</a></li>
			<li role="presentation"><a href="#">ASX 100</a></li>
			<li role="presentation"><a href="#">ASX 200</a></li>
			<li role="presentation"><a href="#">ASX 300</a></li>
			<li role="presentation"><a href="#">All Ords</a></li>
		</ul>
		<div class="row">
			<div class="col-sm-12">
				<div id="metrics">
					@include('layouts.partials.stock-list-display')
				</div>
			</div>
		</div>
	</div>
@stop