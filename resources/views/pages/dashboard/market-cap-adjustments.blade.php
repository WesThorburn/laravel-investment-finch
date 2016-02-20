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
			<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margin-top" id="deleted_stocks">
			    <thead>
			        <tr>
			            <th>Code</th>
			            <th>Name</th>
			            <th>Market Cap Before</th>
			            <th>Market Cap After</th>
			            <th>Stock Price</th>
			            <th>Volume</th>
			        </tr>
			    </thead>
			    <tbody data-link="row" class="rowlink">
				    @foreach($marketCapAdjustments as $stock)
						<tr>
							<td>
								{{ $stock->stock_code }}<a href="/stocks/{{$stock->stock_code}}"></a>
							</td>
							<td>{{ $stock->stock->company_name }}</td>
							<td>{{ $stock->market_cap*1000 }}</td>
							<td>{{ $stock->market_cap }}</td>
							<td>{{ $stock->last_trade }}</td>
							<td>{{ $stock->volume }}</td>
						</tr>
					@endforeach
			    </tbody>
			</table>
		</div>
	</div>

	<script>
		$(document).ready(function(){
			$('#deleted_stocks').DataTable({
				"dom": 'tp',
				"pageLength": 20,
				"lengthMenu": [20,50,100],
				"stateSave": true,
				"order": [[ 3, "desc" ]]
			});
		});
	</script>
@stop