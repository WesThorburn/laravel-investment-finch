<div class="panel panel-default half-margin-bottom">
	<div class="panel-heading"><b>{{ $highestVolumeStocksTitle }}</b></div>
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margins">
		<tbody data-link="row" class="rowlink">
		    @foreach($highestVolumeStocks as $stock)
				<tr>
					<td>
						{{ $stock->stock_code }}<a href="/stock/{{$stock->stock_code}}"></a>
					</td>
					<td>
						{{ $stock->stock->company_name }}
					</td>
					<td>
						{{ number_format($stock->volume) }}
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>