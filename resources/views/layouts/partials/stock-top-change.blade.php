<div class="panel panel-default">
	<div class="panel-heading"><b>{{ $title }}</b></div>
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margins">
		<tbody data-link="row" class="rowlink">
		    @foreach($stockChanges as $stockChange)
				<tr>
					<td>
						{{ $stockChange->stock->stock_code }}<a href="/stock/{{$stockChange->stock->stock_code}}"></a>
					</td>
					<td>
						{{ ucfirst(strtolower($stockChange->stock->company_name)) }}
					</td>
					<td @if($stockChange->week_change < 0) class="color-red" 
						@elseif($stockChange->week_change > 0) class="color-green"
						@endif>
						{{ $stockChange->week_change }}%
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>