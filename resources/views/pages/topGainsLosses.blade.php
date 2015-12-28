@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'topGainsLosses'])
@stop

@section('title')
	Top Gains and Losses
@stop

@section('body')
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<div class="row">
					<div class="col-sm-6">
						@include('layouts.partials.stock-top-change', 
							[
								'stockChanges' => $topWeeklyGains, 
								'title' => 'Best Performing Stocks (7 Days)',
								'timeFrame' => 'week'
							])
					</div>

					<div class="col-sm-6">
						@include('layouts.partials.stock-top-change', 
							[
								'stockChanges' => $topWeeklyLosses, 
								'title' => 'Worst Performing Stocks (7 Days)',
								'timeFrame' => 'week'
							])
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						@include('layouts.partials.stock-top-change', 
							[
								'stockChanges' => $topMonthlyGains, 
								'title' => 'Best Performing Stocks (30 Days)',
								'timeFrame' => 'month'
							])
					</div>

					<div class="col-sm-6">
						@include('layouts.partials.stock-top-change', 
							[
								'stockChanges' => $topMonthlyLosses, 
								'title' => 'Worst Performing Stocks (30 Days)',
								'timeFrame' => 'month'
							])
					</div>
				</div>
			</div>
			<div class="col-md-4 no-padding-left">
				@include('layouts.partials.stock-top-change', 
					[
						'stockChanges' => $topStocksThisYear, 
						'title' => "Best Performing Stocks ".date('Y'),
						'timeFrame' => 'ytd'
					])
			</div>
		</div>
	</div>
@stop