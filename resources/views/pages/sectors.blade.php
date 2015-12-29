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

		function getGraphData(timeFrame, dataType){
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
									<button class="btn btn-default active" onclick="getGraphData('last_month', 'total_market_cap')" id="last_month">30 Days</button>
									<button class="btn btn-default" onclick="getGraphData('last_3_months', 'total_market_cap')" id="last_3_months">3 Months</button>
									<button class="btn btn-default" onclick="getGraphData('last_6_months', 'total_market_cap')" id="last_6_months">6 Months</button>
									<button class="btn btn-default" onclick="getGraphData('last_year', 'total_market_cap')" id="last_year">12 Months</button>
									<button class="btn btn-default" onclick="getGraphData('last_2_years', 'total_market_cap')" id="last_2_years">2 Years</button>
									<button class="btn btn-default" onclick="getGraphData('last_5_years', 'total_market_cap')" id="last_5_years">5 Years</button>
									<button class="btn btn-default" onclick="getGraphData('last_10_years', 'total_market_cap')" id="last_10_years">10 Years</button>
									<button class="btn btn-default" onclick="getGraphData('all_time', 'total_market_cap')" id="all_time">All</button>
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