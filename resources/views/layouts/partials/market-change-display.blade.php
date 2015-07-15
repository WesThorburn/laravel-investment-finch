@if($marketChange > 0)
	<div class="color-green quarter-margin-top">
		The ASX is up <b>{{$marketChange}}%</b> today.
	</div>
@elseif($marketChange < 0)
	<div class="color-red">
		The ASX is down <b>{{$marketChange}}%</b> today.
	</div>
@else
	<div>
		{{$marketChange}}%
	</div>
@endif