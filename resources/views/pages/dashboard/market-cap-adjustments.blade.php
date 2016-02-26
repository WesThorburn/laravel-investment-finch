@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'dashboard'])
@stop

@section('title')
	Dashboard
@stop

@section('body')
	<div class="col-md-6 col-md-offset-3">
		<ul class="nav nav-tabs stocks-page-nav-tabs">
			<li role="presentation"><a href="/dashboard/discontinued">Discontinued Stocks</a></li>
			<li role="presentation" class="active"><a href="/dashboard/marketCapAdjustments">Market Cap Adjustments</a></li>
		</ul>
		<div class="panel panel-default single-pixel-top-margin">
			<div class="panel-heading"><b>Stocks with Adjusted Market Caps (/1000)</b></div>
			<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margin-top" id="adjusted_stocks">
			    <thead>
			        <tr>
			            <th>Code</th>
			            <th>Name</th>
			            <th>Yesterday's Market Cap</th>
			            <th>Current Market Cap</th>
			            <th>Difference</th>
			            <th>Stock Price</th>
			            <th>Volume</th>
			            <th></th>
			        </tr>
			    </thead>
			    <tbody data-link="row" class="rowlink">
				    @foreach($marketCapAdjustments as $stock)
						<tr>
							<td>
								{{ $stock->stock_code }}<a href="/stocks/{{$stock->stock_code}}"></a>
							</td>
							<td>{{ $stock->stock->company_name }}</td>
							<td>{{ number_format($stock->yesterdays_market_cap, 2) }}</td>
							<td>{{ $stock->current_market_cap }}</td>
							<td>{{ number_format(($stock->current_market_cap-$stock->yesterdays_market_cap),2) }}</td>
							<td>{{ $stock->last_trade }}</td>
							<td>{{ $stock->volume }}</td>
							<td>
								{!! Form::open(['method' => 'post', 'action' => ['DashboardController@changeStockAdjustmentStatus', $stock->stock_code]]) !!}
									{!! Form::hidden('adjustment', false) !!}
									{!! Form::hidden('stockCode', $stock->stock_code) !!}
									{!! Form::button("", ['name' => 'removeFromList', 'type' => 'submit', 'class' => 'glyphicon glyphicon-remove center-block', 'aria-hidden' => 'true']) !!}
								{!! Form::close() !!}
							</td>
						</tr>
					@endforeach
			    </tbody>
			</table>
		</div>
	</div>
	<div class="col-md-3 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<b>Add a Stock Code that requires a Market Cap Adjustment</b>
			</div>
			<div class="panel-body">
				{!! Form::open(['action' => 'DashboardController@changeStockAdjustmentStatus', 'method' => 'post', 'class' => 'form-group form-inline']) !!}
					{!! Form::hidden('adjustment', true) !!}
					{!! Form::text('stockCode', null, ['class' => 'form-control', 'placeholder' => 'Add Stock Code']) !!}
					{!! Form::submit("Add", ['class' => 'btn btn-default form-control']) !!}
				{!! Form::close() !!}
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function(){
			$('#adjusted_stocks').DataTable({
				"dom": 'tp',
				"pageLength": 20,
				"lengthMenu": [20,50,100],
				"stateSave": true,
				"order": [[ 4, "asc" ]]
			});
		});
	</script>
@stop