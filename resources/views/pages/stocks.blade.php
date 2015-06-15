@extends('layouts.master')

@section('title')
	Home
@stop

@section('body')
	<script>
		$(document).ready(function(){
			$('#stock_table').DataTable({
				"lengthMenu": [20,50,100]
			});
		});
	</script>
	<div class = "col-md-2 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<label>Filter By Sector</label>
			</div>
			<div class="panel-body">
				{!! Form::open(array('action' => 'SearchController@show', 'method' => 'get')) !!}
					{!! Form::hidden('searchType', 'sectorOnly') !!}
					{!! Form::select('sector', $sectors, $sectorName, ['class' => 'form-control half-margin-bottom']) !!}
					{!! Form::submit("Filter", ['class' => 'btn btn-default form-control']) !!}
				{!! Form::close() !!}
			</div>
		</div>
	</div>
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<table class="table table-striped table-hover table-bordered table-condensed" id="stock_table">
			    <thead>
			        <tr>
			            <th>Code</th>
			            <th>Name</th>
			            <th>Sector</th>
			            <th>Share Price</th>
			            <th>Day Change</th>
			            <th>Mkt Cap</th>
			            <th>Volume</th>
			            <th>EBITDA (m)</th>
			            <th>EPS Current Year</th>
			            <th>EPS Next Year</th>
			            <th>P/E Ratio</th>
			            <th>Price/Book</th>
			            <th>52 Week High</th>
			            <th>52 Week Low</th>
			            <th>50 Day MA</th>
			            <th>200 Day MA</th>
			            <th>Div Yield</th>
			        </tr>
			    </thead>
				<div class="panel-body">
				    <tbody>
				        @foreach($stocks as $stock)
				        	<tr>
				        		<td>{{ $stock->stock_code }}</td>
				        		<td>{{ $stock->stock->company_name }}</td>
				        		<td>{{ $stock->stock->sector }}</td>
				        		<td>${{ $stock->last_trade }}</td>
				        		<td>{{ $stock->day_change }}%</td>
				        		<td>{{ $stock->market_cap }}</td>
				        		<td>{{ $stock->average_daily_volume }}</td>
				        		<td>{{ $stock->EBITDA }}</td>
				        		<td>{{ $stock->earnings_per_share_current }}</td>
				        		<td>{{ $stock->earnings_per_share_next_year }}</td>
				        		<td>{{ $stock->price_to_earnings }}</td>
				        		<td>{{ $stock->price_to_book }}</td>
				        		<td>{{ $stock->year_high }}</td>
				        		<td>{{ $stock->year_low }}</td>
				        		<td>{{ $stock->fifty_day_moving_average }}</td>
				        		<td>{{ $stock->two_hundred_day_moving_average }}</td>
				        		<td>{{ $stock->dividend_yield }}</td>
				        	</tr>
				        @endforeach
				    </tbody>
				</div>
			</table>
		</div>
	</div>
@stop