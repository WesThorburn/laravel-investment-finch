@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'sectors'])
@stop

@section('title')
	Sectors
@stop

@section('body')
	<script>
		$(document).ready(
            function() {
                setInterval(function() {
                	if(window.location.pathname != "/sector"){
                		$('#allSectors').load('/ajax' + window.location.pathname + '/daychanges');
                		$('#otherStocksInSector').load('/ajax' + window.location.pathname + '/otherstocksinsector');
                	}
                }, 60000);
            });

		$(document).ready(function () {
		    $(window).resize(function(){
		        lava.get('SectorCaps', function(){
		        	this.draw;
		        });
		    });
		});

		function getSectorGraphData(timeFrame, dataType){
			$.getJSON('/sectorGraph/'+ '{{ $selectedSector }}/' + timeFrame + '/' + dataType, function (dataTableJson) {
				lava.loadData('SectorCaps', dataTableJson, function (chart) {
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

		function getMarketCapsGraphData(numberOfStocks){
			$.getJSON('stocksInSectorPieChart/'+ '{{ $selectedSector }}/'+ numberOfStocks, function(dataTableJson){
				lava.loadData('SectorStocks', dataTableJson, function(chart){
				});
			});
			var numberOfStocksButtonIds = [
				"top_5", 
				"top_10", 
				"top_15", 
				"top_20", 
				"all"
			];

			numberOfStocksButtonIds.forEach(function(buttonId){
				document.getElementById(buttonId).className = "btn btn-default";
			});
			document.getElementById(numberOfStocks).className = "btn btn-default active";
		}

	</script>
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div id="allSectors">
					@include('layouts.partials.all-sectors-day-change-display')
				</div>
			</div>
			<div class="col-md-8">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<div class="btn-group btn-group-sm pull-center" role="group">
									<button class="btn btn-default active" onclick="getMarketCapsGraphData('top_5')" id="top_5">Top 5</button>
									<button class="btn btn-default" onclick="getMarketCapsGraphData('top_10')" id="top_10">Top 10</button>
									<button class="btn btn-default" onclick="getMarketCapsGraphData('top_15')" id="top_15">Top 15</button>
									<button class="btn btn-default" onclick="getMarketCapsGraphData('top_20')" id="top_20">Top 20</button>
									<button class="btn btn-default" onclick="getMarketCapsGraphData('all')" id="all">All</button>
								</div>
							</div>
							<div class="panel-body">
								<div id="sector_stocks_div" class="pull-left">
									@piechart('SectorStocks', 'sector_stocks_div')
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<div class="btn-group btn-group-sm pull-center" role="group">
									<button class="btn btn-default active" onclick="getSectorGraphData('last_month', 'Market Cap')" id="last_month">30 Days</button>
									<button class="btn btn-default" onclick="getSectorGraphData('last_3_months', 'Market Cap')" id="last_3_months">3 Months</button>
									<button class="btn btn-default" onclick="getSectorGraphData('last_6_months', 'Market Cap')" id="last_6_months">6 Months</button>
									<button class="btn btn-default" onclick="getSectorGraphData('last_year', 'Market Cap')" id="last_year">12 Months</button>
									<button class="btn btn-default" onclick="getSectorGraphData('last_2_years', 'Market Cap')" id="last_2_years">2 Years</button>
									<button class="btn btn-default" onclick="getSectorGraphData('last_5_years', 'Market Cap')" id="last_5_years">5 Years</button>
									<button class="btn btn-default" onclick="getSectorGraphData('last_10_years', 'Market Cap')" id="last_10_years">10 Years</button>
									<button class="btn btn-default" onclick="getSectorGraphData('all_time', 'Market Cap')" id="all_time">All</button>
								</div>
							</div>
							<div class="panel-body">
								<div id="sector_caps_div" class="pull-left">
									@areachart('SectorCaps', 'sector_caps_div')
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div id="otherStocksInSector">
							@include('layouts.partials.other-stocks-in-sector')
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop