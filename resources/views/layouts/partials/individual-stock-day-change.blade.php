<h3
	@if(rtrim($dayChange, '%') < 0) 
		class="side-by-side color-red" 
	@elseif(rtrim($dayChange, '%') > 0) 
		class="side-by-side color-green" 
	@endif>
	{{ $dayChange }}%
</h3>