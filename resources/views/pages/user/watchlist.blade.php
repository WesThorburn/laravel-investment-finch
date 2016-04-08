@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'watchlist'])
@stop

@section('title')
	Watchlist
@stop

@section('body')
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						Your Watchlists
					</div>
					@if($watchlists->first())
						<table class="table table-striped table-hover table-bordered table-condensed no-margins" id="watchlists_table">
							<tbody data-link="row" class="rowlink">
								@foreach($watchlists as $watchlist)
									<tr @if($watchlist->id == $selectedWatchlist->id) class="table-row-active" @endif>
										<td>
											<a href="{{$watchlist->id}}">{{$watchlist->watchlist_name}}</a>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					@else
						<div class="panel-body">
							You have no watchlists yet. 
						</div>
					@endif
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						Create a Watchlist
					</div>
					<div class="panel-body">
						<!-- Name -->
						<form role="form" action="{{action('WatchlistController@store')}}" method="POST">
							{{ csrf_field() }}
							<div class="col-xs-12">
								<label for="name">Watchlist Name</label>
							</div>
							<div class="col-xs-12">
								<input name="watchlistName" id="watchlistName" type="text" class="form-control{{ $errors->has('watchlistName') ? ' has-error' : ''}}" placeholder="Watchlist Name" maxlength="64" value={{ old('watchlistName') }}>
							</div>
							<div class="col-xs-12 half-margin-top half-margin-bottom">
								<button type="submit" class="btn btn-default">Create</button>
							</div>
						</form>
						@if($errors->has('watchlistName'))
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
						@if(Session::has('watchlistCreateSuccess'))
							<div class="col-xs-12 quarter-margin-top">
								<div class="alert alert-success three-quarter-margin-bottom">
									<ul>
							            <li>{{ Session('watchlistCreateSuccess') }}</li>
							        </ul>
								</div>
							</div>
						@elseif(Session::has('watchlistNameError'))
							<div class="col-xs-12 quarter-margin-top">
								<div class="alert alert-danger three-quarter-margin-bottom">
									<ul>
							            <li>{{ Session('watchlistNameError') }}</li>
							        </ul>
								</div>
							</div>
						@endif
					</div>
				</div>
			</div>
			@if($watchlists->first())
				<div class="col-md-9">
					@include('layouts.partials.watchlist-stocks')
					<div class="col-md-6 no-padding-left">
						<div class="panel panel-default">
							<div class="panel-heading">
								Add a Stock to this Watchlist
							</div>
							<div class="panel-body">
								<!-- Add Stock to Watchlist -->
								<form role="form" action="{{action('WatchlistController@update', ['id' => $selectedWatchlist->id])}}" method="POST">
									<input type="hidden" name="_method" value="put"/>
									{{ csrf_field() }}
									<div class="row">
										<label class="col-xs-12 single-px-padding-right" for="stockCode">Stock Code</label>
									</div>
									<div class="row">
										<div class="col-xs-10 single-px-padding-right">
											<input name="stockCode" id="stockCode" type="text" class="form-control{{ $errors->has('stockCode') ? ' has-error' : ''}}" 
											placeholder="Code" maxlength="3" value={{ old('stockCode') }}>
										</div>
										<div class="col-xs-2 single-px-padding-left">
											<button type="submit" class="btn btn-default">Add</button>
										</div>
									</div>
								</form>
								@if($errors->has('stockCode'))
									<div class="col-xs-12 default-margin-top">
										<div class="alert alert-danger three-quarter-margin-bottom">
											<ul>
									            @foreach ($errors->all() as $error)
									                <li>{{ $error }}</li>
									            @endforeach
									        </ul>
										</div>
									</div>
								@endif
								@if(Session::has('addStockToWatchlistSuccess'))
									<div class="col-xs-12 default-margin-top">
										<div class="alert alert-success three-quarter-margin-bottom">
											<ul>
									            <li>{{ Session('addStockToWatchlistSuccess') }}</li>
									        </ul>
										</div>
									</div>
								@elseif(Session::has('watchlistError'))
									<div class="col-xs-12 default-margin-top">
										<div class="alert alert-danger three-quarter-margin-bottom">
											<ul>
									            <li>{{ Session('watchlistError') }}</li>
									        </ul>
										</div>
									</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			@endif
		</div>
	</div>
@stop