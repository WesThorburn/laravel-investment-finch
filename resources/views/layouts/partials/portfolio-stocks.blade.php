<div class="panel panel-default three-quarter-margin-bottom">
	<div class="panel-heading">Stocks in {{ $selectedPortfolio->portfolio_name }}</div>
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margin-top responsive" cellspacing="0" width="100%" id="stocks_in_portfolio_table">
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
	            <th class="all"></th>
	        </tr>
	    </thead>
	    <tbody data-link="row" class="rowlink">
	    	<?php 
	    		//Initialize total value variables
	    		$totalValue = 0;
	    		$totalGainLoss = 0;
	    	?>
		    @foreach($stocksInSelectedPortfolio as $stock)
		    	<!-- Calculate Portfolio row values -->
		    	<?php 
		    		$currentValue = $stock->last_trade*$stock->quantity; 
		    		$purchaseValue = $stock->purchase_price*$stock->quantity;
		    		$gainLoss = $currentValue-$purchaseValue;
		    		$percentGainLoss = 100/$purchaseValue*$gainLoss;
		    		$valueChange = $stock->quantity*$stock->day_change;

		    		//Totals
		    		$totalValue = $totalValue+$currentValue;
		    		$totalGainLoss = $totalGainLoss+$gainLoss;
		    	?>
				<tr>
					<td>
						{{ $stock->stock_code }}<a href="/stocks/{{$stock->stock_code}}"></a>
					</td>
					<td>{{ number_format($stock->quantity) }}</td>
					<td>{{ number_format($stock->purchase_price, 2) }}</td>
					<td>{{ number_format($stock->last_trade, 2) }}</td>
					<td>{{ number_format(($currentValue), 2) }}</td>
					<td @if($gainLoss < 0) class="color-red" 
						@elseif($gainLoss > 0) class="color-green"
						@endif>
						{{ number_format($gainLoss, 2) }}
					</td>
					<td @if($percentGainLoss < 0) class="color-red" 
						@elseif($percentGainLoss > 0) class="color-green"
						@endif>
						{{ number_format($percentGainLoss, 2) }}
					</td>
					<td @if($stock->day_change < 0) class="color-red" 
						@elseif($stock->day_change > 0) class="color-green"
						@endif>
						{{ number_format($stock->day_change, 2) }}
					</td>
					<td @if($valueChange < 0) class="color-red" 
						@elseif($valueChange > 0) class="color-green"
						@endif>
						{{ number_format($valueChange, 2) }}
					</td>
					<td class="rowlink-skip">
						<button type="button" id="openSellModal" class="btn btn-danger btn-half-padding" data-toggle="modal" data-target="#sellModal" data-stockcode="{{ $stock->stock_code }}" data-currentprice="{{ number_format($stock->last_trade, 2) }}">Sell</button>
					</td>
				</tr>
			@endforeach
			<tfoot>
				<tr class="table-subtotals-row">
					<th><b>SubTotals:</b></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</tfoot>
	    </tbody>
	</table>
</div>

<script>
	//Table contents
	$(document).ready(function(){
		$('#stocks_in_portfolio_table').DataTable({
			"dom": 'tp',
			"pageLength": 15,
			"lengthMenu": [5,10,15,20,50,100],
			"stateSave": true,
			"footerCallback": function () {
	            var api = this.api();
	 
	            // Remove the formatting to get integer data for summation
	            var intVal = function ( i ) {
	                return typeof i === 'string' ?
	                    i.replace(/[\$,]/g, '')*1 :
	                    typeof i === 'number' ?
	                        i : 0;
	            };
	 
	            // Total over all pages
	            var columnTotal = function(columnNumber, api){
	            	return api
	                .column( columnNumber )
	                .data()
	                .reduce( function (a, b) {
	                    return intVal(a) + intVal(b);
	                }, 0 );
	            }

	            var getTotalPercentGainLoss = function(totalCurrentValue, totalCurrentGainLoss){
	            	return (100/(totalCurrentValue-totalCurrentGainLoss)*totalCurrentGainLoss).toFixed(2);
	            }

	            var getClassForDisplay = function(value){
	            	if(value > 0){
	            		return "color-green";
	            	}
	            	else if(value < 0){
	            		return "color-red";
	            	}
	            	else{
	            		return "";
	            	}
	            }

	            var totalCurrentValue = columnTotal(4, api);
	            var totalCurrentGainLoss = columnTotal(5, api);
	            var totalPercentGainLoss = getTotalPercentGainLoss(totalCurrentValue, totalCurrentGainLoss);
	            var totalCurrentDayGainLoss = columnTotal(8, api);
	 
	            // Update footer
	            $( api.column( 4 ).footer() ).html(
	                '$'+ totalCurrentValue.toLocaleString()
	            );
	            $( api.column( 5 ).footer() ).html(
	                '<div class='+ getClassForDisplay(totalCurrentGainLoss) +'>$'+ totalCurrentGainLoss.toLocaleString()+'</div>'
	            );
	            $( api.column( 6 ).footer() ).html(
	                '<div class='+ getClassForDisplay(totalPercentGainLoss) +'>'+ totalPercentGainLoss.toLocaleString()+'%</div>'
	            );
	            $( api.column( 8 ).footer() ).html(
	                '<div class='+ getClassForDisplay(totalCurrentDayGainLoss) +'>$'+ totalCurrentDayGainLoss.toLocaleString()+'</div>'
	            );
        	}
		});
	});
</script>