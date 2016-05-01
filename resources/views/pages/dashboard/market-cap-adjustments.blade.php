@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'dashboard'])
@stop

@section('title')
	Dashboard
@stop

@section('body')
	<div class="container">
		<div class="row">
			<div class="col-md-8 no-padding-right">
				<ul class="nav nav-tabs stocks-page-nav-tabs">
					<li role="presentation"><a href="/dashboard/discontinued">Discontinued Stocks</a></li>
					<li role="presentation" class="active"><a href="/dashboard/marketCapAdjustments">Market Cap Adjustments</a></li>
				</ul>
				<div class="panel panel-default single-pixel-top-margin three-quarter-margin-bottom">
					<div class="panel-heading"><b>All Stocks</b></div>
					<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margin-top" id="adjusted_stocks">
					    <thead>
					        <tr>
					            <th>Code</th>
					            <th>Name</th>  
					            <th>Yesterday's Mkt Cap (M)</th>          
					            <th>Current Mkt Cap (M)</th>
					            <th>Difference (M)</th>
					            <th>Day Change</th>
					            <th>Requires Adjustment</th>
					            <th></th>
					        </tr>
					    </thead>
					    <div class="panel-body">
					    	<tbody></tbody>
					    </div>
					</table>
				</div>
				@if($flaggedStocks->first())
					<div class="panel panel-default">
						<div class="panel-heading"><b>Flagged Stocks</b></div>
						<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margin-top" id="flagged_stocks">
						    <thead>
						        <tr>
						            <th>Code</th>
						            <th>Yesterday's Mkt Cap (M)</th>          
						            <th>Current Mkt Cap (M)</th>
						            <th>Difference (M)</th>
						            <th>Day Change</th>
						            <th>Requires Adjustment</th>
						        </tr>
						    </thead>
						    <tbody data-link="row" class="rowlink">
							    @foreach($flaggedStocks as $stock)
									<tr>
										<td>
											{{ $stock->stock_code }}<a href="/stocks/{{$stock->stock_code}}"></a>
										</td>
										<td>{{ $stock->yesterdays_market_cap }}</td>
										<td>{{ $stock->current_market_cap }}</td>
										<td>{{ $stock->current_market_cap - $stock->yesterdays_market_cap }}</td>
										<td>{{ $stock->percent_change }}</td>
										<td>
											@if($stock->market_cap_requires_adjustment == 0)
												No
											@elseif($stock->market_cap_requires_adjustment == 1)
												Yes
											@endif
										</td>
									</tr>
								@endforeach
						    </tbody>
						</table>
					</div>
				@endif
			</div>
			<div class="col-md-4 double-margin-top">
				<div class="panel panel-default single-pixel-top-margin">
					<div class="panel-heading"><b>Admin Options</b></div>
					<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margins">
						<thead>
							<th>Option Name</th>
							<th>Value</th>
							<th></th>
						</thead>
						<tbody>
							@foreach($adminOptions as $option)
								<td>{{ $option->option_name }}</td>
								<td>
									@if(!$option->option_value)
										<div class="color-green">
											None
										</div>
									@else
										<div class="color-red">
											{{ $option->option_value }}
										</div>
									@endif
								</td>
								<td>
									<form role="form" action="{{action('DashboardController@adminOptions')}}" method="POST">
										{{ csrf_field() }}
										<input type="hidden" name="{{ $option->option_name }}" value="true">
										<button type="submit" class="btn btn-default pull-right" aria-label="Left Align">
											<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
										</button>
									</form>
								</td>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function(){
			var stockTable = $('#adjusted_stocks').DataTable({
				processing: true,
				serverSide: true,
				ajax: '/dashboard/ajax/marketCapAdjustments',
				lengthMenu: [20,50,100],
				"order": [[ 4, "desc" ]],
				fnColumnCallback: function(nColumn){
					console.log(nColumn);
				},
				columns: [
					{data: 'stock_code', name: 'stocks.stock_code'},
					{data: 'company_name', name: 'company_name'},
					{data: 'yesterdays_market_cap', name: 'yesterdays_market_cap', searchable: false},
		            {data: 'current_market_cap', name: 'current_market_cap', searchable: false},
		            {data: 'difference', name: 'difference', searchable: false},
		            {data: 'percent_change', name: 'percent_change', searchable: false},
		            {data: 'market_cap_requires_adjustment', name: 'market_cap_requires_adjustment'},
		            {data: 'change_adjustment', name: 'change_adjustment', searchable: false}
				]
			});		
			//Make table rows clickable
			$('#stock_table').delegate('tbody > tr', 'click', function(){
				var data = stockTable.row(this).data();
				window.location.assign('/stock/'+ data.stock_code)
			});
		});
	</script>
@stop