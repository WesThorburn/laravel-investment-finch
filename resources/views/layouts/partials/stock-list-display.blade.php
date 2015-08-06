<div class="panel panel-default">
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom" id="stock_table">
	    <thead>
	        <tr>
	            <th>Code</th>
	            <th>Name</th>
	            <th>Sector</th>
	            <th>Share Price</th>
	            <th>Day Change</th>
	            <th>Mkt Cap (M)</th>
	            <th>Volume</th>
	            <th>EBITDA (m)</th>
	            <th>EPS Current Year</th>
	            <th>P / E Ratio</th>
	            <th>Price / Book</th>
	            <th>52 Week High</th>
	            <th>52 Week Low</th>
	        </tr>
	    </thead>
		<div class="panel-body">
		    <tbody data-link="row" class="rowlink">
			    @foreach($stocks as $stock)
					<tr>
						<td>
							{{ $stock->stock_code }}<a href="/stocks/{{$stock->stock_code}}"></a>
						</td>
						<td>{{ ucfirst(strtolower($stock->stock->company_name)) }}</td>
						<td>{{ $stock->stock->sector }}</td>
						<td>${{ $stock->last_trade }}</td>
						<td @if($stock->day_change < 0) class="color-red" 
							@elseif($stock->day_change > 0) class="color-green"
							@endif>
							{{ $stock->day_change }}%
						</td>
						<td>{{ $stock->market_cap }}</td>
						<td>{{ $stock->average_daily_volume }}</td>
						<td>{{ $stock->EBITDA }}</td>
						<td>{{ $stock->earnings_per_share_current }}</td>
						<td>{{ $stock->price_to_earnings }}</td>
						<td>{{ $stock->price_to_book }}</td>
						<td>{{ $stock->year_high }}</td>
						<td>{{ $stock->year_low }}</td>
					</tr>
				@endforeach
		    </tbody>
		</div>
	</table>
</div>

<script>
	$(document).ready(function(){
		$('#stock_table').DataTable({
			"lengthMenu": [20,50,100],
			"stateSave": true
		});
	});
</script>