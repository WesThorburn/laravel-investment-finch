@extends('layouts.master')

@section('body')
	<div class="col-md-8 col-md-offset-2">
		<h1>Dashboard</h1>
		@foreach($discontinuedStocks as $stock)
			<p>{{ $stock->stock_code }}</p>
		@endforeach
	</div>
@stop