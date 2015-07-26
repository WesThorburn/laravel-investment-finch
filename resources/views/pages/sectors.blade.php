@extends('layouts.master')

@section('title')
	Screener
@stop

@section('body')
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="panel panel-default">
					<table class="table table-striped table-hover table-bordered table-condensed no-margins" id="sector_table">
						<thead>
					        <tr>
					            <th>Sector</th>
					            <th>{{ $sectorWeekDay }}'s Change</th>
					        </tr>
					    </thead>
					    <tbody data-link="row" class="rowlink">
							@foreach($sectors as $sector)
								<tr @if($sector->sector == $selectedSector) class="table-row-active" @endif>
									<td>
										<a href="/sector/{{$sector->sector}}">{{$sector->sector}}</a>
									</td>
									<td>
										<div 
											@if($sector->day_change < 0) class="color-red" 
												@elseif($sector->day_change > 0) class="color-green"
											@endif>
											{{ $sector->day_change }}%
										</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-8">
				<div id="metrics">
					@if($selectedSectorDayChange < 0.00)
						<?php $sortOrder = 'asc'; ?>
					@elseif($selectedSectorDayChange >= 0.00)
						<?php $sortOrder = 'desc'; ?>
					@endif
					@include('layouts.partials.other-stocks-in-sector', ['sortOrder' => $sortOrder])
				</div>
			</div>
		</div>
	</div>

<script>
	$(document).ready(function(){
		$('#sector_table').DataTable({
			"pageLength": 30,
			"dom": '',
			"stateSave": true
		});
	});
</script>
@stop