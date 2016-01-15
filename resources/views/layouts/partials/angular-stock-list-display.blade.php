<div class="container" ng-app="stocksApp" ng-controller="stocksController">
	<div class="row">
		<div class="panel panel-default">
			<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom" id="stock_table">
			    <thead>
			        <tr>
			            <th>Code</th>
			            <th>Name</th>
			            <th>Sector</th>
			            <th>Share Price</th>
			            <th>Day Change</th>
			            <th>Mkt Cap (M)</th>
			            <th>Volume</th>
			            <th>EBITDA (m)</th>
			            <th>EPS Current Year</th>
			            <th>P / E Ratio</th>
			            <th>Price / Book</th>
			            <th>52 Week High</th>
			            <th>52 Week Low</th>
			        </tr>
			    </thead>
				<div class="panel-body">
				    <tbody>
				    	<tr ng-repeat="stock in stocks" ng-click="showStock(stock.stock_code)">
				    		<td>@{{ stock.stock_code }}</td>
				    		<td>@{{ stock.stock.company_name }}</td>
				    		<td>@{{ stock.stock.sector }}</td>
				    		<td>@{{ stock.last_trade }}</td>
				    		<td>
								@{{ stock.day_change }}%
							</td>
							<td>@{{ stock.market_cap }}</td>
							<td>@{{ stock.average_daily_volume }}</td>
							<td>@{{ stock.EBITDA }}</td>
							<td>@{{ stock.earnings_per_share_current }}</td>
							<td>@{{ stock.price_to_earnings }}</td>
							<td>@{{ stock.price_to_book }}</td>
							<td>@{{ stock.year_high }}</td>
							<td>@{{ stock.year_low }}</td>
				    	</tr>
				    </tbody>
				</div>
			</table>
		</div>
		<button class="btn btn-primary" ng-click="nextPage()">Next</button>
	</div>
</div>

<script>
	var stocksApp = angular.module('stocksApp', []);
	stocksApp.controller('stocksController', function($scope, $http){
		$scope.stocks = [];
		$scope.lastpage = 1;

		$scope.init = function(){
			$scope.lastpage = 1;
			$http({
				url: '/ajax/stocks',
				method: 'GET',
				params: {page: $scope.lastpage}
			}).success(function(data, status, headers, config){
				$scope.stocks = data.data;
				$scope.currentpage = data.current_page;
			});
		};

		$scope.nextPage = function(){
			$scope.lastpage += 1;
			$http({
				url: '/ajax/stocks',
				method: 'GET',
				params: {page: $scope.lastpage}
			}).success(function(data, status, headers, config){
				$scope.stocks = data.data;
			});
		};

		$scope.showStock = function(stockCode, location){
			$location.path('/stock/'+stockCode);
		}

		$scope.init();
	});
</script>