@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => ''])
@stop

@section('title')
	{{$stock->metrics->stock_code}}
@stop

@section('body')
	<script type="text/javascript">
		$(document).ready(
            function() {
                setInterval(function() {
                		$('#relatedStocks').load('/ajax/relatedstocks/{{$stock->metrics->stock_code}}');
                		$('#stockPrice').load('/ajax/stock/currentPrice/{{$stock->metrics->stock_code}}');
                		$('#dayChange').load('/ajax/stock/dayChange/{{$stock->metrics->stock_code}}');
                }, 60000);
        	});

		$(document).ready(function () {
		    $(window).resize(function(){
		        lava.get('StockPrice', function(){
		        	this.draw;
		        });
		    });
		});

		function getGraphData(timeFrame, dataType){
			$.getJSON('/ajax/graph/stock/'+ '{{ $stock->stock_code }}/' + timeFrame + '/' + dataType, function (dataTableJson) {
				lava.loadData('StockPrice', dataTableJson, function (chart) {
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
			<div class="col-lg-12">
				<h1 class="no-margin-top">{{ $stock->company_name }}</h1>
				<h2>{{ $stock->sector }}</h2>
				<h3>(ASX: {{ $stock->stock_code }})</h3>

				<div class="default-margin-top"></div>

				<h2 class="side-by-side quater-margin-right" id="stockPrice">${{ $metrics->last_trade }}</h2>
				<h3 id="dayChange"
					@if(rtrim($metrics->day_change, '%') < 0) 
						class="side-by-side color-red" 
					@elseif(rtrim($metrics->day_change, '%') > 0) 
						class="side-by-side color-green" 
					@else
						class="side-by-side"
					@endif>
					{{ $metrics->day_change }}%
				</h3>

				<div class="default-margin-bottom"></div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-md-7">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="btn-group btn-group-sm pull-center" role="group">
							<button class="btn btn-default active" onclick="getGraphData('last_month', 'Price')" id="last_month">30 Days</button>
							<button class="btn btn-default" onclick="getGraphData('last_3_months', 'Price')" id="last_3_months">3 Months</button>
							<button class="btn btn-default" onclick="getGraphData('last_6_months', 'Price')" id="last_6_months">6 Months</button>
							<button class="btn btn-default" onclick="getGraphData('last_year', 'Price')" id="last_year">12 Months</button>
							<button class="btn btn-default" onclick="getGraphData('last_2_years', 'Price')" id="last_2_years">2 Years</button>
							<button class="btn btn-default" onclick="getGraphData('last_5_years', 'Price')" id="last_5_years">5 Years</button>
							<button class="btn btn-default" onclick="getGraphData('last_10_years', 'Price')" id="last_10_years">10 Years</button>
							<button class="btn btn-default" onclick="getGraphData('all_time', 'Price')" id="all_time">All</button>
						</div>
					</div>
					<div class="panel-body">
						<div id="stock_price_div" class="pull-left">
							@linechart('StockPrice', 'stock_price_div')
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-5">
				<div class="panel panel-default">
					<div class="panel-heading"><b>Key Metrics</b></div>
					<table class="table table-bordered">
						<tbody>
							<tr>
								<td></td>
								<td><b>{{ $stock->stock_code }}</b></td>
								<td><b>Sector</b></td>
							</tr>
							<tr>
								<td>EBITDA</td>
								<td>{{ $metrics->EBITDA }}</td>
								<td>-</td>
							</tr>
							<tr>
								<td>EPS (This Year)</td>
								<td>{{ $metrics->earnings_per_share_current }}</td>
								<td>{{ $sectorAverage->earnings_per_share_current }}</td>
							</tr>
							<tr>
								<td>EPS (Next Year)</td>
								<td>{{ $metrics->earnings_per_share_next_year }}</td>
								<td>{{ $sectorAverage->earnings_per_share_next_year }}</td>
							</tr>
							<tr>
								<td>Price/Earnings</td>
								<td>{{ $metrics->price_to_earnings }}</td>
								<td>{{ $sectorAverage->price_to_earnings }}</td>
							</tr>
							<tr>
								<td>Price/Book</td>
								<td>{{ $metrics->price_to_book }}</td>
								<td>{{ $sectorAverage->price_to_book }}</td>
							</tr>
							<tr>
								<td>52 Week High</td>
								<td>{{ $metrics->year_high }}</td>
								<td>-</td>
							</tr>
							<tr>
								<td>52 Week Low</td>
								<td>{{ $metrics->year_low }}</td>
								<td>-</td>
							</tr>
							<tr>
								<td>50 Day Moving Average</td>
								<td>
									@if($mostRecentStockHistoricals)
										{{ $mostRecentStockHistoricals->fifty_day_moving_average }}
									@endif
								</td>
								<td>-</td>
							</tr>
							<tr>
								<td>200 Day Moving Average</td>
								<td>
									@if($mostRecentStockHistoricals)
										{{ $mostRecentStockHistoricals->two_hundred_day_moving_average }}
									@endif
								</td>
								<td>-</td>
							</tr>
							<tr>
								<td>Market Cap</td>
								<td>{{ formatMoneyAmountToLetter($metrics->market_cap) }}</td>
								<td>{{ formatMoneyAmountToLetter(round($sectorAverage->average_sector_market_cap, 2)) }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="row">
			<div @if($stock->business_summary == "") class="col-md-12" @else class="col-md-7" @endif>
				<div class="row">
					<div class="col-md-12">
						@if($relatedStocks->first())
							<div id="relatedStocks">
								@include('layouts.partials.related-stock-list-display')
							</div>
						@endif
					</div>
					<div class="col-md-12">
						@if($metrics->analysis != "")
							<div class="panel panel-default">
								<div class="panel-heading">
									<b>Stock Analysis</b>
								</div>
								<div class="panel-body">
									{{ $metrics->analysis }}
								</div>
							</div>
						@endif
					</div>
				</div>
			</div>
			<div @if(!$relatedStocks->first() && $metrics->analysis != "") class="col-md-12" @else class="col-md-5" @endif>
				@if($stock->business_summary != "")
					<div class="panel panel-default">
						<div class="panel-heading">
							<b>Business Summary</b>
						</div>
						<div class="panel-body">
							{{ $stock->business_summary }}
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>


@stop

@section('footer')
	
@stop