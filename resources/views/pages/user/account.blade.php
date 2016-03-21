@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'account'])
@stop

@section('title')
	{{ Auth::user()->name }}'s Account
@stop

@section('body')
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						Account Information
					</div>
					<div class="panel-body">
						<!-- Name -->
						<form role="form" action="{{action('UserController@update', ['id' => Auth::user()->id])}}" method="POST">
							<input type="hidden" name="_method" value="put"/>
							<input type="hidden" name="fieldToBeUpdated" value="name"/>
							{{ csrf_field() }}
							<label class="col-xs-3 text-right" for="name">Your Name</label>
							<div class="col-xs-7">
								<input name="name" id="name" type="text" class="form-control{{ $errors->has('name') ? ' has-error' : ''}}" 
								placeholder="Your Name" value="{{Auth::user()->name}}" maxlength="64">
							</div>
							<div class="col-xs-2 pull-left">
								<button type="submit" class="btn btn-default">Save</button>
							</div>
						</form>
						@if($errors->has('name'))
							<div class="col-xs-12 quarter-margin-top">
								<div class="alert alert-danger">
									<ul>
							            @foreach ($errors->all() as $error)
							                <li>{{ $error }}</li>
							            @endforeach
							        </ul>
								</div>
							</div>
						@endif
						@if(Session::has('nameChangeSuccess'))
							<div class="col-xs-12 quarter-margin-top">
								<div class="alert alert-success">
									<ul>
							            <li>{{ Session('nameChangeSuccess') }}</li>
							        </ul>
								</div>
							</div>
						@endif
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						Password
					</div>
					<div class="panel-body">
						<!-- Password -->
						<form role="form" action="{{action('UserController@update', ['id' => Auth::user()->id])}}" method="POST">
							<input type="hidden" name="_method" value="put"/>
							<input type="hidden" name="fieldToBeUpdated" value="password"/>
							{{ csrf_field() }}
							<!-- Current Password -->
							<div class="col-xs-12 quarter-margin-top">
								<label class="col-xs-4 text-right" for="oldPassword">Current Password</label>
								<div class="col-xs-8">
									<input name="oldPassword" id="oldPassword" type="password" class="form-control{{ $errors->has('oldPassword') ? ' has-error' : ''}}" 
									placeholder="Your Current Password" maxlength="64">
								</div>
							</div>

							<!-- New Password 1 -->
							<div class="col-xs-12 quarter-margin-top">
								<label class="col-xs-4 text-right" for="password">New Password</label>
								<div class="col-xs-8">
									<input name="password" id="password" type="password" class="form-control{{ $errors->has('password') ? ' has-error' : ''}}" 
									placeholder="Your New Password" maxlength="64">
								</div>
							</div>

							<!-- New Password 2 -->
							<div class="col-xs-12 quarter-margin-top">
								<label class="col-xs-4 text-right" for="password_confirmation">New Password Again</label>
								<div class="col-xs-8">
									<input name="password_confirmation" id="password_confirmation" type="password" class="form-control{{ $errors->has('password') ? ' has-error' : ''}}" 
									placeholder="Your New Password Again" maxlength="64">
								</div>
							</div>

							<div class="col-xs-12 quarter-margin-top pull-right">
								<div class="col-xs-4 col-xs-offset-4">
									<button type="submit" class="btn btn-default">Change Password</button>
								</div>
							</div>
						</form>
						@if($errors->has('currentPassword') || $errors->has('password') || $errors->has('password_confirmation'))
							<div class="col-xs-12 quarter-margin-top">
								<div class="alert alert-danger">
									<ul>
							            @foreach ($errors->all() as $error)
							                <li>{{ $error }}</li>
							            @endforeach
							        </ul>
								</div>
							</div>
						@endif
						@if(Session::has('passwordChangeSuccess'))
							<div class="col-xs-12 quarter-margin-top">
								<div class="alert alert-success">
									<ul>
							            <li>{{ Session('passwordChangeSuccess') }}</li>
							        </ul>
								</div>
							</div>
						@elseif(Session::has('passwordChangeFailure'))
							<div class="col-xs-12 quarter-margin-top">
								<div class="alert alert-danger">
									<ul>
							            <li>{{ Session('passwordChangeFailure') }}</li>
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