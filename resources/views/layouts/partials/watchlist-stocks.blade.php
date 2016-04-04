<div class="panel panel-default">
	<div class="panel-heading"><b>Stocks in {{ $selectedWatchlist->watchlist_name }}</b></div>
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margin-top" id="watchlist_table">
	    <thead>
	        <tr>
	            <th>Code</th>
	            <th>Name</th>
	            <th>Share Price</th>
	            <th>Day Change</th>
	            <th>Mkt Cap (M)</th>
	            <th>Volume</th>
	            <th>EBITDA (m)</th>
	            <th>EPS Current Year</th>
	            <th>P / E Ratio</th>
	            <th>Price / Book</th>
	            <th>PEG Ratio</th>
	            <th>52 Week High</th>
	            <th>52 Week Low</th>
	        </tr>
	    </thead>
		<tbody data-link="row" class="rowlink">
			@foreach($stocksInSelectedWatchlist as $stock)
				<tr>	
					<td>{{ $stock->stock_code }}<a href="/stocks/{{$stock->stock_code}}"></td>
					<td class="td-limit-small">{{ $stock->stock->company_name }}</td>
					<td>${{ $stock->last_trade }}</td>
					<td @if($stock->percent_change < 0) class="color-red" 
						@elseif($stock->percent_change > 0) class="color-green"
						@endif>
						{{ number_format($stock->percent_change, 2) }}%
					</td>
					<td>{{ $stock->volume }}</td>
					<td>{{ $stock->EBITDA }}</td>
					<td>{{ $stock->earnings_per_share_current }}</td>
					<td>{{ $stock->price_to_earnings }}</td>
					<td>{{ $stock->price_to_book }}</td>
					<td>{{ $stock->peg_ratio }}</td>
					<td>{{ $stock->current_market_cap }}</td>
					<td>{{ $stock->year_high }}</td>
					<td>{{ $stock->year_low }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>

<script>
	$(document).ready(function(){
		$('#watchlist_table').DataTable({
			"dom": 'tp',
			"pageLength": 10,
			"lengthMenu": [10,20,50,100],
			"stateSave": true
		});
	});
</script>