@extends('layouts.master')

@section('title')
	{{$stock->stock->stock_code}}
@stop

@section('body')
	<script type="text/javascript">
		function getGraphData(timeFrame){
			$.getJSON('/graph/'+ '{{ $stock->stock_code }}/' + timeFrame, function (dataTableJson) {
				console.log(dataTableJson);
				lava.loadData('StockPrice', dataTableJson, function (chart) {
					console.log(chart);
				});
			});
		}
	</script>
	<div class="col-md-6 col-md-offset-3">
		<div class="center-block">
			<h1>{{ $stock->company_name }}</h1>
			<h2>{{ $stock->sector }}</h2>
			<h3>{{ $stock->stock_code }}</h3>
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading">Key Metrics</div>
					<table class="table table-bordered">
						<tbody>
							<tr>
								<td>EBITDA</td>
								<td>{{ $metrics->EBITDA }}</td>
							</tr>
							<tr>
								<td>EPS (This Year)</td>
								<td>{{ $metrics->earnings_per_share_current }}</td>
							</tr>
							<tr>
								<td>EPS (Next Year)</td>
								<td>{{ $metrics->earnings_per_share_next_year }}</td>
							</tr>
							<tr>
								<td>Price/Earnings</td>
								<td>{{ $metrics->price_to_earnings }}</td>
							</tr>
							<tr>
								<td>Price/Book</td>
								<td>{{ $metrics->price_to_book }}</td>
							</tr>
							<tr>
								<td>52 Week High</td>
								<td>{{ $metrics->year_high }}</td>
							</tr>
							<tr>
								<td>52 Week Low</td>
								<td>{{ $metrics->year_low }}</td>
							</tr>
							<tr>
								<td>50 Day Moving Average</td>
								<td>{{ $metrics->fifty_day_moving_average }}</td>
							</tr>
							<tr>
								<td>200 Day Moving Average</td>
								<td>{{ $metrics->two_hundred_day_moving_average }}</td>
							</tr>
							<tr>
								<td>Market Cap (M)</td>
								<td>{{ $metrics->market_cap }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-9">
				<h2>Last Trade: {{ $metrics->last_trade }}</h2>
				<h3 @if($metrics->day_change < 0) class="color-red" @elseif($metrics->day_change > 0) class="color-green" @endif>
					Day Change: {{ $metrics->day_change }}%
				</h3>
				<div class="container center-block">
					<div class="btn-group btn-group-sm" role="group">
						<button class="btn btn-default active" onclick="getGraphData('last_month')">30 Days</button>
						<button class="btn btn-default" onclick="getGraphData('last_3_months')">3 Months</button>
						<button class="btn btn-default" onclick="getGraphData('last_6_months')">6 Months</button>
						<button class="btn btn-default" onclick="getGraphData('last_year')">12 Months</button>
						<button class="btn btn-default" onclick="getGraphData('last_2_years')">2 Years</button>
						<button class="btn btn-default" onclick="getGraphData('last_5_years')">5 Years</button>
						<button class="btn btn-default" onclick="getGraphData('last_10_years')">All</button>
						<button class="btn btn-default" onclick="getGraphData('all_time')">10 Years</button>
					</div>
				</div>
				<div class="container">
					<div id="stock_price_div" class="pull-left">
						@areachart('StockPrice', 'stock_price_div')
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@section('footer')
	
@stop