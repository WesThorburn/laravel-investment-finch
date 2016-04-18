@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'register'])
@stop

@section('title')
	Register
@stop

@section('body')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading">Register</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="{{ old('name') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Confirm Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-default">
									Register
								</button>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-8 col-md-offset-4">
								<p>Already have an account? <a href="login">Log In here.</a></p>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
