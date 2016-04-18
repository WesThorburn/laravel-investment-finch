<div class="pull-right half-margin-top hidden-xs">
	{!! Form::open(['action' => 'StockController@show', 'method' => 'get', 'class' => 'form-group form-inline no-margin-bottom']) !!}
		{!! Form::text('stockCodeFind', null, ['class' => 'form-control', 'placeholder' => 'Find Stock Code']) !!}
		{!! Form::submit("Find", ['class' => 'btn btn-default form-control']) !!}
	{!! Form::close() !!}
</div>

<span class="default-margin-bottom default-margin-top visible-xs">
	{!! Form::open(['action' => 'StockController@show', 'method' => 'get', 'class' => 'form-group form-inline']) !!}
		{!! Form::text('stockCodeFind', null, ['class' => 'third-width form-control', 'placeholder' => 'Find Stock Code']) !!}
		{!! Form::submit("Find", ['class' => 'btn btn-default third-width form-control quarter-margin-top']) !!}
	{!! Form::close() !!}
</span>