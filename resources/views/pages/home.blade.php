@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'home'])
@stop

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
                }, 60000);
            });

		$(document).ready(function () {
		    $(window).resize(function(){
		        lava.get('MarketCaps', function(){
		        	this.draw;
		        });
		    });
		});

		function getMarketCapGraphData(timeFrame, dataType){
			$.getJSON('/sectorGraph/'+ 'All/' + timeFrame + '/' + dataType, function (dataTableJson) {
				lava.loadData('MarketCaps', dataTableJson, function (chart) {
				});
			});
			var timeFrameButtonIds = [
				"last_month", 
				"last_3_months", 
				"last_6_months", 
				"last_year", 
				"last_2_years", 
				"last_5_years", 
				"last_10_years", 
				"all_time"
			];

			timeFrameButtonIds.forEach(function(buttonId){
				document.getElementById(buttonId).className = "btn btn-default";
			});
			document.getElementById(timeFrame).className = "btn btn-default active";
		}

		function getSectorCapGraphData(numberOfSectors, dataType){
			$.getJSON('/sectorCapsPieChart/'+ numberOfSectors, function (dataTableJson) {
				lava.loadData('Sectors', dataTableJson, function (chart) {
				});
			});
			var numberOfSectorsButtonIds = [
				"top_5", 
				"top_10", 
				"top_15",
				"top_20",
				"all"
			];

			numberOfSectorsButtonIds.forEach(function(buttonId){
				document.getElementById(buttonId).className = "btn btn-default";
			});
			document.getElementById(numberOfSectors).className = "btn btn-default active";
		}
	</script>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="btn-group btn-group-sm pull-center" role="group">
							<button class="btn btn-default active" onclick="getMarketCapGraphData('last_month', 'Market Cap')" id="last_month">30 Days</button>
							<button class="btn btn-default" onclick="getMarketCapGraphData('last_3_months', 'Market Cap')" id="last_3_months">3 Months</button>
							<button class="btn btn-default" onclick="getMarketCapGraphData('last_6_months', 'Market Cap')" id="last_6_months">6 Months</button>
							<button class="btn btn-default" onclick="getMarketCapGraphData('last_year', 'Market Cap')" id="last_year">12 Months</button>
							<button class="btn btn-default" onclick="getMarketCapGraphData('last_2_years', 'Market Cap')" id="last_2_years">2 Years</button>
							<button class="btn btn-default" onclick="getMarketCapGraphData('last_5_years', 'Market Cap')" id="last_5_years">5 Years</button>
							<button class="btn btn-default" onclick="getMarketCapGraphData('last_10_years', 'Market Cap')" id="last_10_years">10 Years</button>
							<button class="btn btn-default" onclick="getMarketCapGraphData('all_time', 'Market Cap')" id="all_time">All</button>
						</div>
					</div>
					<div class="panel-body">
						<div id="market_performance_div" class="pull-left">
							@areachart('MarketCaps', 'market_performance_div')
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="row">
					<div id="sectorDayGain">
						@include('layouts.partials.sector-day-change-display', ['sectorChanges' => $sectorDayGains, 'title' => $sectorDayGainTitle])
					</div>
				</div>
				<div class="row">
					<div id="sectorDayLoss">
						@include('layouts.partials.sector-day-change-display', ['sectorChanges' => $sectorDayLosses, 'title' => $sectorDayLossTitle])
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="btn-group btn-group-sm pull-center" role="group">
							<button class="btn btn-default active" onclick="getSectorCapGraphData('top_5', 'Sector Cap')" id="top_5">Top 5</button>
							<button class="btn btn-default" onclick="getSectorCapGraphData('top_10', 'Sector Cap')" id="top_10">Top 10</button>
							<button class="btn btn-default" onclick="getSectorCapGraphData('top_15', 'Sector Cap')" id="top_15">Top 15</button>
							<button class="btn btn-default" onclick="getSectorCapGraphData('top_20', 'Sector Cap')" id="top_20">Top 20</button>
							<button class="btn btn-default" onclick="getSectorCapGraphData('all', 'Sector Cap')" id="all">All</button>
						</div>
					</div>
					<div class="panel-body">
						<div id="sectors_pie_div" class="pull-left">
							@piechart('Sectors', 'sectors_pie_div')
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop