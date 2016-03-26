<div class="panel panel-default">
	<div class="panel-heading"><b>Stocks in {{ $selectedPortfolio->portfolio_name }}</b></div>
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margin-top" id="stock_table">
	    <thead>
	        <tr>
	            <th>Code</th>
	            <th>Quantity</th>
	            <th>Purchase</th>
	            <th>Current</th>
	            <th>Value($)</th>
	            <th>Gain/Loss($)</th>
	            <th>Gain/Loss(%)</th>
	            <th>Day Change</th>
	            <th>Value Change</th>
	        </tr>
	    </thead>
	    <tbody data-link="row" class="rowlink">
		    @foreach($stocksInSelectedPortfolio as $stock)
				<tr>
					<td>
						{{ $stock->stock_code }}<a href="/stocks/{{$stock->stock_code}}"></a>
					</td>
					<td>{{ number_format($stock->purchase_qty) }}</td>
					<td>${{ number_format($stock->purchase_price, 2) }}</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			@endforeach
	    </tbody>
	</table>
</div>

<script>
	$(document).ready(function(){
		$('#stock_table').DataTable({
			"dom": 'tp',
			"pageLength": 10,
			"lengthMenu": [5,10,20,50,100],
			"stateSave": true
		});
	});
</script>