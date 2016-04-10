<h2 class="side-by-side quarter-margin-right quarter-margin-bottom">${{ $metrics->last_trade }}</h2>
<h3
	@if($metrics->day_change < 0) 
		class="side-by-side color-red" 
	@elseif($metrics->day_change > 0) 
		class="side-by-side color-green" 
	@else
		class="side-by-side"
	@endif>

	@if($metrics->day_change >= 0)
		+{{ $metrics->day_change }}
	@else
		{{ $metrics->day_change }}
	@endif
</h3>
<h3 id="dayPercentChange"
	@if(rtrim($metrics->percent_change, '%') < 0) 
		class="side-by-side color-red" 
	@elseif(rtrim($metrics->percent_change, '%') > 0) 
		class="side-by-side color-green" 
	@else
		class="side-by-side"
	@endif>
	({{ number_format($metrics->percent_change, 2) }}%)
</h3>