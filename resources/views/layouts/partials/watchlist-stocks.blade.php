<div class="panel panel-default">
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom" id="watchlist_table">
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
	            <th>PEG Ratio</th>
	            <th>52 Week High</th>
	            <th>52 Week Low</th>
	        </tr>
	    </thead>
		<tbody data-link="row" class="rowlink">
			@foreach()
				<tr>

				</tr>
			@endforeach
		</tbody>
	</table>
</div>

<script>
	$(document).ready(function(){
		$('#watchlist_table').DataTable({
			"dom": 'tp',
			"pageLength": 5,
			"lengthMenu": [5,10,20,50,100],
			"stateSave": true
		});
	});
</script>