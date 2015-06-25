@extends('layouts.master')

@section('title')
	Home
@stop

@section('body')
	<script>
		$(document).ready(
            function() {
                setInterval(function() {
                	if(window.location.search == ""){
                		$('#metrics').load('/search/%7Bsearch%7D?viewType=partial');
                	}
                	else{
						$('#metrics').load('/search/%7Bsearch%7D'+window.location.search+'&viewType=partial');
                	}
                }, 10000);
            });
	</script>
	<div class = "col-md-3 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<label>Filter By Sector</label>
			</div>
			<div class="panel-body">
				{!! Form::open(['action' => 'SearchController@show', 'method' => 'get']) !!}
					{!! Form::select('stockSector', $stockSectors, $stockSectorName, ['class' => 'form-control half-margin-bottom']) !!}
					{!! Form::label('omitConditionLabel', 'Show stocks with low quality or incomplete metrics') !!}
					{!! Form::checkbox('omitCondition', null, false, ['class' => 'quater-margin-left']) !!}
					{!! Form::submit("Filter", ['class' => 'btn btn-default form-control half-margin-top']) !!}
				{!! Form::close() !!}
			</div>
		</div>
	</div>
	<div class="col-md-10 col-md-offset-1">
		<div id="metrics">
			@include('layouts.partials.stock-list-display')
		</div>
	</div>

	
@stop