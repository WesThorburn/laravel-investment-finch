<div class="pull-right half-margin-top">
	{!! Form::open(['action' => 'StockController@show', 'method' => 'get', 'class' => 'form-group form-inline']) !!}
		{!! Form::text('stockCodeFind', null, ['class' => 'form-control', 'placeholder' => 'Find Stock Code']) !!}
		{!! Form::submit("Find", ['class' => 'btn btn-default form-control']) !!}
	{!! Form::close() !!}
</div>