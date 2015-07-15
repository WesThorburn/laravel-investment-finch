<div class="panel panel-default">
	<div class="panel-heading 2px-padding"><b>Sector Performance</b></div>
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margins" id="sector_day_change_table">
	    <thead>
	        <tr>
	            <th>Sector</th>
	            <th>Day Change</th>
	        </tr>
	    </thead>
	    <tbody>
		    @foreach($sectorDayChanges as $sectorDayChange)
				<tr>
					<td>
						{{ $sectorDayChange->sector }}
					</td>
					<td @if($sectorDayChange->day_change < 0) class="color-red" 
						@elseif($sectorDayChange->day_change > 0) class="color-green"
						@endif>
						{{ $sectorDayChange->day_change }}%
					</td>
				</tr>
			@endforeach
	    </tbody>
	</table>
</div>

<script>
	$(document).ready(function(){
		$('#sector_day_change_table').DataTable({
			"dom": '',
			"pageLength": 5,
			"lengthMenu": [5,10,20,30],
			"stateSave": true
		});
	});
</script>