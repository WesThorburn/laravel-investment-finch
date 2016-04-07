@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'performance'])
@stop

@section('title')
	Performance
@stop

@section('body')
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<div class="row">
					<div class="col-sm-6 no-padding-right">
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
					<div class="col-sm-6 no-padding-right">
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
						'stockChanges' => $topStocks12Months, 
						'title' => "Best Performing Stocks (12 Months)",
						'timeFrame' => '12months'
					])
			</div>
		</div>
	</div>
@stop