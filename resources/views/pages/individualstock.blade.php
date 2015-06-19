@extends('layouts.master')

@section('title')
	{{$stock->stock->stock_code}}
@stop

@section('body')
	<div class="col-md-6 col-md-offset-3">
		<div class="center-block">
			<canvas id="myChart" width="850" height="300"></canvas>
		</div>
	</div>

	<script>
		var data = {
		    labels: {{$dates}},
		    datasets: [
		        {
		            label: "My Second dataset",
		            fillColor: "rgba(151,187,205,0.2)",
		            strokeColor: "rgba(151,187,205,1)",
		            pointColor: "rgba(151,187,205,1)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(151,187,205,1)",
		            data: {{$prices}}
		        }
		    ]
		};
		// Get context with jQuery - using jQuery's .get() method.
		var ctx = $("#myChart").get(0).getContext("2d");
		// This will get the first returned node in the jQuery collection.
		var myLineChart = new Chart(ctx).Line(data);

		
	</script>
@stop