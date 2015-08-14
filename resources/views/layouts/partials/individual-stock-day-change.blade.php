<h3
	@if(rtrim($dayChange, '%') < 0) 
		class="side-by-side color-red" 
	@elseif(rtrim($dayChange, '%') > 0) 
		class="side-by-side color-green"
	@else
		class="side-by-side"
	@endif>
	{{ $dayChange }}%
</h3>