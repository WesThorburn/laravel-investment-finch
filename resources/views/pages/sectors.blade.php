@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'sectors'])
@stop

@section('title')
	Sectors
@stop

@section('body')
	<script>
		$(document).ready(
            function() {
                setInterval(function() {
                	if(window.location.pathname != "/sector"){
                		$('#allSectors').load('/ajax' + window.location.pathname + '/daychanges');
                		$('#otherStocksInSector').load('/ajax' + window.location.pathname + '/otherstocksinsector');
                	}
                }, 5000);
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