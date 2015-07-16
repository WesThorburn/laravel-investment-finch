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
                	$('#sectorDayGain').load('/search/%7Bsearch%7D?viewType=partial&section=sectorDayGain');
                	$('#sectorDayLoss').load('/search/%7Bsearch%7D?viewType=partial&section=sectorDayLoss');
                }, 10000);
            });
	</script>
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div id="sectorDayGain">
					@include('layouts.partials.sector-day-change-display', ['sectorChanges' => $sectorDayGains, 'title' => $sectorDayGainTitle])
				</div>
			</div>

			<div class="col-md-4">
				<div id="sectorDayLoss">
					@include('layouts.partials.sector-day-change-display', ['sectorChanges' => $sectorDayLosses, 'title' => $sectorDayLossTitle])
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-4">
			@include('layouts.partials.stock-top-change', ['stockChanges' => $topWeeklyGains, 'title' => 'Best Performing Stocks (7 Days)'])
			</div>

			<div class="col-md-4">
				@include('layouts.partials.stock-top-change', ['stockChanges' => $topWeeklyLosses, 'title' => 'Worst Performing Stocks (7 Days)'])
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="pull-left">
					{!! Form::open(['action' => 'SearchController@show', 'method' => 'get', 'class' => 'form-group form-inline']) !!}
						{!! Form::select('stockSector', $stockSectors, $stockSectorName, ['class' => 'form-control']) !!}
						{!! Form::submit("Filter Sector", ['class' => 'btn btn-default form-control']) !!}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div id="metrics">
					@include('layouts.partials.stock-list-display')
				</div>
			</div>
		</div>
	</div>
	
@stop