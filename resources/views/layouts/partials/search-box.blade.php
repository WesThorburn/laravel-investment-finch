<div class="pull-right half-margin-top hidden-xs">
	{!! Form::open(['action' => 'StockController@show', 'method' => 'get', 'class' => 'form-group form-inline no-margin-bottom', 'role' => 'search']) !!}
		{!! Form::text('term', null, ['class' => 'form-control', 'placeholder' => 'Search for Stocks...', 'id' => 'term']) !!}
		{!! Form::submit("Find", ['class' => 'btn btn-default form-control']) !!}
	{!! Form::close() !!}
</div>

<span class="default-margin-bottom default-margin-top visible-xs">
	{!! Form::open(['action' => 'StockController@show', 'method' => 'get', 'class' => 'form-group form-inline', 'role' => 'search']) !!}
		{!! Form::text('term', null, ['class' => 'third-width form-control', 'placeholder' => 'Search for Stocks...', 'id' => 'term']) !!}
		{!! Form::submit("Find", ['class' => 'btn btn-default third-width form-control quarter-margin-top']) !!}
	{!! Form::close() !!}
</span>

<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script>
	$(function(){
		$("#term").autocomplete({
			source: "{{ route('search.autocomplete') }}",
			minLength: 1,
			select: function(event, ui){
				$('#term').val(ui.item.value);
			}
		});
	});
</script>