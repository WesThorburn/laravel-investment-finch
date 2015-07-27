<span @if($marketStatus == "Market Open")
	class="color-green"
	@elseif($marketStatus == "Market Closed")
	class="color-red"
	@endif>
	<b>{{ $marketStatus }}</b>
</span> <b> - {{ $serverTime }}