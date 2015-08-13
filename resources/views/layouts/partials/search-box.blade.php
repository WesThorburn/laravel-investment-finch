<div class="pull-right half-margin-top">
	{!! Form::open(['action' => 'StockController@show', 'method' => 'get', 'class' => 'form-group form-inline']) !!}
		{!! Form::text('stockCodeSearch', null, ['class' => 'form-control', 'placeholder' => 'Stock Code']) !!}
		{!! Form::submit("Search", ['class' => 'btn btn-default form-control']) !!}
	{!! Form::close() !!}
</div>