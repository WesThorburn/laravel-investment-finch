@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'account'])
@stop

@section('title')
	{{ Auth::user()->name }}'s' Account
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
						<form action="{{action('UserController@update', ['id' => Auth::user()->id])}}" method="POST">
							<input type="hidden" name="_method" value="put"/>
							{{ csrf_field() }}
							<div class="form-group">
								<label for="name">Your Name</label>
								<input name="name" id="name" type="text" class="form-control" placeholder="Your Name" value="{{Auth::user()->name}}">
							</div>
							<button type="submit" class="btn btn-primary">Submit</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop