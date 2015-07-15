<div class="panel panel-default">
	<div class="panel-heading"><b>{{ $title }}</b></div>
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margins" id="sector_day_change_table">
	    @foreach($sectorChanges as $sectorChange)
			<tr>
				<td>
					{{ $sectorChange->sector }}
				</td>
				<td @if($sectorChange->day_change < 0) class="color-red" 
					@elseif($sectorChange->day_change > 0) class="color-green"
					@endif>
					{{ $sectorChange->day_change }}%
				</td>
			</tr>
		@endforeach
	</table>
</div>