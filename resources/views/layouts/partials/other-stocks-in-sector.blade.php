<div class="panel panel-default">
	<div class="panel-heading"><b>{{$selectedSector}}</b></div>
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margin-top" id="stocks_in_sector">
	    <thead>
	        <tr>
	            <th>Code</th>
	            <th>Name</th>
	            <th>Share Price</th>
	            <th>Day Change</th>
	            <th>Mkt Cap (M)</th>
	        </tr>
	    </thead>
	    <tbody data-link="row" class="rowlink">
		    @foreach($stocksInSector as $stock)
				<tr>
					<td>
						<a href="/stocks/{{$stock->stock_code}}">{{ $stock->stock_code }}</a>
					</td>
					<td>{{ $stock->stock->company_name }}</td>
					<td>${{ $stock->last_trade }}</td>
					<td @if($stock->day_change < 0) class="color-red" 
						@elseif($stock->day_change > 0) class="color-green"
						@endif>
						{{ $stock->day_change }}%
					</td>
					<td>{{ $stock->market_cap }}</td>
				</tr>
			@endforeach
	    </tbody>
	</table>
</div>

<script>
	$(document).ready(function(){
		$('#stocks_in_sector').DataTable({
			"pageLength": 12,
			"dom": 'tp',
			"stateSave": true
		});
	});
</script>