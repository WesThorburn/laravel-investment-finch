@if($marketChangeMessage)
	@if($marketChange > 0)
		<div class="color-green quarter-margin-top">
			{{$marketChangeMessage}}
		</div>
	@elseif($marketChange < 0)
		<div class="color-red">
			{{$marketChangeMessage}}
		</div>
	@endif
@endif