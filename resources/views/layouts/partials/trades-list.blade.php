<div class="panel panel-default">
	<div class="panel-heading">Your Trades</div>
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margin-top responsive" cellspacing="0" width="100%" id="trades_table">
	    <thead>
	        <tr>
	        	<th>Trade Type</th>
	            <th>Code</th>
	            <th>Price($)</th>
	            <th>Quantity</th>
	            <th>Brokerage($)</th>
	            <th>Value($)</th>
	            <th>Date</th>
	        </tr>
	    </thead>
	    <tbody>
		    @foreach($trades as $trade)
				<tr>
					<td @if($trade->trade_type == "sell") class="color-red" 
						@elseif($trade->trade_type == "buy") class="color-green"
						@endif>
						{{ ucfirst($trade->trade_type) }}
					</td>
					<td>{{ $trade->stock_code }}</td>
					<td>{{ number_format($trade->price, 2) }}</td>
					<td>{{ number_format($trade->quantity) }}</td>
					<td>{{ number_format($trade->brokerage, 2) }}</td>
					<td>
						@if($trade->trade_type == "buy")
							{{ number_format($trade->price * $trade->quantity + $trade->brokerage, 2) }}
						@elseif($trade->trade_type == "sell")
							{{ number_format($trade->price * $trade->quantity - $trade->brokerage, 2) }}
						@endif
					</td>
					<td>{{ $trade->date }}</td>
				</tr>
			@endforeach
	    </tbody>
	</table>
</div>
<script>
	$(document).ready(function(){
		$('#trades_table').DataTable({
			"dom": 'tp',
			"pageLength": 15,
			"lengthMenu": [5,10,15,20,50,100],
			"stateSave": true
		});
	});
</script>