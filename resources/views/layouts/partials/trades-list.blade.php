<div class="panel panel-default">
	<div class="panel-heading">Your Trades</div>
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margin-top" id="stocks_in_portfolio_table">
	    <thead>
	        <tr>
	        	<th>Trade Type</th>
	            <th>Code</th>
	            <th>Price</th>
	            <th>Quantity</th>
	            <th>Brokerage</th>
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
						{{ $trade->trade_type }}
					</td>
					<td>{{ $trade->stock_code }}</td>
					<td>{{ $trade->price }}</td>
					<td>{{ $trade->quantity }}</td>
					<td>{{ $trade->brokerage }}</td>
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