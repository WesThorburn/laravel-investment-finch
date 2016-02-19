@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'dashboard'])
@stop

@section('title')
	Dashboard
@stop

@section('body')
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading"><b>Discontinued Stocks</b></div>
			<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margin-top" id="deleted_stocks">
			    <thead>
			        <tr>
			            <th>Code</th>
			            <th>Name</th>
			            <th>Sector</th>
			            <th>Deleted At</th>
			        </tr>
			    </thead>
			    <tbody data-link="row" class="rowlink">
				    @foreach($discontinuedStocks as $stock)
						<tr>
							<td>
								{{ $stock->stock_code }}<a href="/stocks/{{$stock->stock_code}}"></a>
							</td>
							<td>{{ $stock->company_name }}</td>
							<td>{{ $stock->sector }}</td>
							<td>{{ $stock->deleted_at }}</td>
						</tr>
					@endforeach
			    </tbody>
			</table>
		</div>
	</div>

	<script>
		$(document).ready(function(){
			$('#deleted_stocks').DataTable({
				"dom": 'tp',
				"pageLength": 20,
				"lengthMenu": [20,50,100],
				"stateSave": true,
				"order": [[ 3, "desc" ]]
			});
		});
	</script>
@stop