<div class="panel panel-default half-margin-bottom">
	<div class="panel-heading"><b>{{ $title }}</b></div>
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margins">
		<tbody data-link="row" class="rowlink">
		    @foreach($sectorChanges as $sectorChange)
				<tr>
					<td>
						{{ $sectorChange->sector }}<a href="/sectors/{{$sectorChange->sector}}"></a>
					</td>
					<td @if($sectorChange->day_change < 0) class="color-red" 
						@elseif($sectorChange->day_change > 0) class="color-green"
						@endif>
						{{ $sectorChange->day_change }}%
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>