<div class="panel panel-default three-quarter-margin-bottom">
	<div class="panel-heading"><b>{{ $title }}</b></div>
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margins">
		<tbody data-link="row" class="rowlink">
		    @foreach($stockChanges as $stockChange)
				<tr>
					<td>
						{{ $stockChange->stock->stock_code }}<a href="/stocks/{{$stockChange->stock->stock_code}}"></a>
					</td>
					<td>
						{{ $stockChange->stock->company_name }}
					</td>
					@if($timeFrame == 'week')
						<td @if($stockChange->week_change < 0) class="color-red" 
							@elseif($stockChange->week_change > 0) class="color-green"
							@endif>
							{{ $stockChange->week_change }}%
						</td>
					@elseif($timeFrame == '12months')
						<td @if($stockChange->year_change < 0) class="color-red" 
							@elseif($stockChange->year_change > 0) class="color-green"
							@endif>
							{{ $stockChange->year_change }}%
						</td>
					@elseif($timeFrame == 'month')
						<td @if($stockChange->month_change < 0) class="color-red" 
							@elseif($stockChange->month_change > 0) class="color-green"
							@endif>
							{{ $stockChange->month_change }}%
						</td>
					@endif
				</tr>
			@endforeach
		</tbody>
	</table>
</div>