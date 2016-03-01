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
	            {data: 'change_adjustment', name: 'change_adjustment'}
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