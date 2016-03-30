<div class="panel panel-default">
	<div class="panel-heading">Stocks in {{ $selectedPortfolio->portfolio_name }}</div>
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
	            <th></th>
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
		    		$currentValue = $stock->last_trade*$stock->purchase_qty; 
		    		$purchaseValue = $stock->purchase_price*$stock->purchase_qty;
		    		$gainLoss = $currentValue-$purchaseValue;
		    		$percentGainLoss = 100/$purchaseValue*$gainLoss;
		    		$valueChange = $stock->purchase_qty*$stock->day_change;

		    		//Totals
		    		$totalValue = $totalValue+$currentValue;
		    		$totalGainLoss = $totalGainLoss+$gainLoss;
		    	?>
				<tr>
					<td>
						{{ $stock->stock_code }}<a href="/stocks/{{$stock->stock_code}}"></a>
					</td>
					<td>{{ number_format($stock->purchase_qty) }}</td>
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

<!-- Sell Modal -->
<div id="sellModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Sell </h4>
			</div>
			<div class="modal-body">
				<!-- Add Stock to Portfolio -->
				<form role="form" action="{{action('PortfolioController@update', ['id' => $selectedPortfolio->id])}}" method="POST">
					<input type="hidden" name="_method" value="put"/>
					<input type="hidden" name="tradeType" value="sell"/>
					{{ csrf_field() }}
					<div class="row">
						<label class="col-sm-2 single-px-padding-right" for="stockCode">Stock Code</label>
						<label class="col-sm-2 single-px-padding-left-right" for="salePrice">Sale Price</label>
						<label class="col-sm-2 single-px-padding-left-right" for="purchaseQty">Quantity</label>
						<label class="col-sm-2 single-px-padding-left-right" for="brokerage">Brokerage</label>
						<label class="col-sm-3 single-px-padding-left-right" for="date">Sale Date</label>
					</div>
					<div class="row row-no-margin-right">
						<div class="col-sm-2 single-px-padding-right">
							<input name="stockCode" id="stockCode" type="text" class="form-control{{ $errors->has('stockCode') ? ' has-error' : ''}}" 
							placeholder="Code" maxlength="3" value={{ old('stockCode') }}>
						</div>
						<div class="col-sm-2 single-px-padding-left-right">
							<div class="input-group">
								<div class="input-group-addon">$</div>
								<input name="salePrice" id="salePrice" type="text" class="form-control{{ $errors->has('salePrice') ? ' has-error' : ''}}" value={{ old('salePrice') }}>
							</div>
						</div>
						<div class="col-sm-2 single-px-padding-left-right">
							<input name="saleQuantity" id="saleQuantity" type="text" class="form-control{{ $errors->has('saleQuantity') ? ' has-error' : ''}}" value={{ old('saleQuantity') }}>
						</div>
						<div class="col-sm-2 single-px-padding-left-right">
							<div class="input-group">
								<div class="input-group-addon">$</div>
								<input name="saleBrokerage" id="saleBrokerage" value="19.95" type="text" class="form-control{{ $errors->has('saleBrokerage') ? ' has-error' : ''}}" value={{ old('saleBrokerage') }}>
							</div>
						</div>
						<div class="col-sm-3 single-px-padding-left-right">
							<input name="saleDate" id="saleDate" type="date" class="form-control{{ $errors->has('saleDate') ? ' has-error' : ''}}" value={{ old('saleDate') }}>
						</div>
						<div class="col-sm-1 single-px-padding-left">
							<button type="submit" class="btn btn-default">Add</button>
						</div>
					</div>
				</form>
				@if($errors->has('stockCode') || $errors->has('salePrice') || $errors->has('saleQuantity') || $errors->has('saleBrokerage') || $errors->has('saleDate'))
					<!-- Display Modal if errors are present -->
					<script type="text/javascript">
					    $('#sellModal').modal('show');
					</script>
					<div class="col-sm-12 default-margin-top">
						<div class="alert alert-danger three-quarter-margin-bottom">
							<ul>
					            @foreach ($errors->all() as $error)
					                <li>{{ $error }}</li>
					            @endforeach
					        </ul>
						</div>
					</div>
				@endif
				@if(Session::has('sellStockSuccess'))
					<div class="col-sm-12 default-margin-top">
						<div class="alert alert-success three-quarter-margin-bottom">
							<ul>
					            <li>{{ Session('addStockToPortfolioSuccess') }}</li>
					        </ul>
						</div>
					</div>
				@elseif(Session::has('sellPortfolioError'))
					<script type="text/javascript">
					    $('#sellModal').modal('show');
					</script>
					<div class="col-sm-12 default-margin-top">
						<div class="alert alert-danger three-quarter-margin-bottom">
							<ul>
					            <li>{{ Session('sellPortfolioError') }}</li>
					        </ul>
						</div>
					</div>
				@endif
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
	//Sell Modal contents
	$("#sellModal").on('show.bs.modal', function(e){
		var stockCode = e.relatedTarget.dataset.stockcode;
		var salePrice = e.relatedTarget.dataset.currentprice
		$(e.currentTarget).find('input[name="stockCode"]').val(stockCode);
		$(e.currentTarget).find('input[name="salePrice"]').val(salePrice);
	})

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