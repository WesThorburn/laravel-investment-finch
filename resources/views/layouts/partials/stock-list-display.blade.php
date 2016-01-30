<ul class="nav nav-tabs">
	<li role="presentation" class="active"><a href="/stocks/all">All Stocks</a></li>
	<li role="presentation"><a href="/stocks/asx20">ASX 20</a></li>
	<li role="presentation"><a href="/stocks/asx50">ASX 50</a></li>
	<li role="presentation"><a href="/stocks/asx100">ASX 100</a></li>
	<li role="presentation"><a href="/stocks/asx200">ASX 200</a></li>
	<li role="presentation"><a href="/stocks/asx300">ASX 300</a></li>
	<li role="presentation"><a href="/stocks/allOrds">All Ords</a></li>
</ul>
<div class="panel panel-default">
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom" id="stock_table">
	    <thead>
	    	<h1 class="stocks-page-header text-center">All Stocks</h1>
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
    		<tbody></tbody>
    	</div>
	</table>
</div>

<script>
	$(document).ready(function(){
		var stockTable = $('#stock_table').DataTable({
			processing: true,
			serverSide: true,
			ajax: '/ajax/stocks',
			lengthMenu: [20,50,100],
			fnColumnCallback: function(nColumn){
				console.log(nColumn);
			},
			columns: [
				{data: 'stock_code', name: 'stocks.stock_code'},
				{data: 'company_name', name: 'company_name'},
				{data: 'sector', name: 'sector'}, 
				{data: 'last_trade', name: 'last_trade', searchable: false}, 
	            {data: 'day_change', name: 'day_change', searchable: false}, 
	            {data: 'market_cap', name: 'market_cap', searchable: false}, 
	            {data: 'average_daily_volume', name: 'average_daily_volume', searchable: false}, 
	            {data: 'EBITDA', name: 'EBITDA', searchable: false}, 
	            {data: 'earnings_per_share_current', name: 'earnings_per_share_current', searchable: false}, 
	            {data: 'price_to_earnings', name: 'price_to_earnings', searchable: false}, 
	            {data: 'price_to_book', name: 'price_to_book', searchable: false}, 
	            {data: 'year_high', name: 'year_high', searchable: false}, 
	            {data: 'year_low', name: 'year_low', searchable: false}, 
			]
		});		
		//Make table rows clickable
		$('#stock_table').delegate('tbody > tr', 'click', function(){
			var data = stockTable.row(this).data();
			window.location.assign('/stock/'+ data.stock_code)
		});

	});
</script>