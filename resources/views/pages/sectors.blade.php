@extends('layouts.master')

@section('title')
	Sectors
@stop

@section('body')
	<script>
		$(document).ready(
            function() {
                setInterval(function() {
                	if(window.location.pathname != "/sector"){
                		$('#allSectors').load(window.location.pathname + '/daychanges');
                		$('#otherStocksInSector').load(window.location.pathname + '/otherstocksinsector');
                	}
                }, 60000);
            });
	</script>
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div id="allSectors">
					@include('layouts.partials.all-sectors-day-change-display')
				</div>
			</div>
			<div class="col-md-8">
				<div id="otherStocksInSector">
					@include('layouts.partials.other-stocks-in-sector')
				</div>
			</div>
		</div>
	</div>
@stop