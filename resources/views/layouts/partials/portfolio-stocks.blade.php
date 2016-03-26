<div class="panel panel-default">
	<div class="panel-heading"><b>Stocks in {{ $selectedPortfolio->portfolio_name }}</b></div>
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margin-top" id="stocks_in_portfolio_table">
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
		    	<!-- Calculate Portfolio row values -->
		    	<?php 
		    		$currentValue = $stock->last_trade*$stock->purchase_qty; 
		    		$purchaseValue = $stock->purchase_price*$stock->purchase_qty+$stock->brokerage;
		    		$gainLoss = $currentValue-$purchaseValue;
		    		$percentGainLoss = 100/$purchaseValue*$gainLoss;
		    		$valueChange = $stock->purchase_qty*$stock->day_change;
		    	?>
				<tr>
					<td>
						{{ $stock->stock_code }}<a href="/stocks/{{$stock->stock_code}}"></a>
					</td>
					<td>{{ number_format($stock->purchase_qty) }}</td>
					<td>{{ number_format($stock->purchase_price, 2) }}</td>
					<td>{{ number_format($stock->last_trade, 2) }}</td>
					<td>{{ number_format(($currentValue), 2) }}</td>
					<td>{{ number_format($gainLoss, 2) }}</td>
					<td>{{ number_format($percentGainLoss, 2) }}</td>
					<td>{{ number_format($stock->day_change, 2) }}</td>
					<td>{{ number_format($valueChange, 2) }}</td>
				</tr>
			@endforeach
	    </tbody>
	</table>
</div>

<script>
	$(document).ready(function(){
		$('#stocks_in_portfolio_table').DataTable({
			"dom": 'tp',
			"pageLength": 10,
			"lengthMenu": [5,10,20,50,100],
			"stateSave": true
		});
	});
</script>