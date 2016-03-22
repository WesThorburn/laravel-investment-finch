@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'portfolio'])
@stop

@section('title')
	Portfolio
@stop

@section('body')
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						Create a portfolio
					</div>
					<div class="panel-body">
						<!-- Name -->
						<form role="form" action="{{action('PortfolioController@store')}}" method="POST">
							{{ csrf_field() }}
							<div class="col-xs-12">
								<label for="name">Portfolio Name</label>
							</div>
							<div class="col-xs-12">
								<input name="portfolioName" id="portfolioName" type="text" class="form-control{{ $errors->has('portfolioName') ? ' has-error' : ''}}" placeholder="Portfolio Name" maxlength="64">
							</div>
							<div class="col-xs-12 half-margin-top half-margin-bottom">
								<button type="submit" class="btn btn-default">Create</button>
							</div>
						</form>
						@if($errors->has('portfolioName'))
							<div class="col-xs-12 quarter-margin-top">
								<div class="alert alert-danger three-quarter-margin-bottom">
									<ul>
							            @foreach ($errors->all() as $error)
							                <li>{{ $error }}</li>
							            @endforeach
							        </ul>
								</div>
							</div>
						@endif
						@if(Session::has('portfolioCreateSuccess'))
							<div class="col-xs-12 quarter-margin-top">
								<div class="alert alert-success three-quarter-margin-bottom">
									<ul>
							            <li>{{ Session('portfolioCreateSuccess') }}</li>
							        </ul>
								</div>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@stop