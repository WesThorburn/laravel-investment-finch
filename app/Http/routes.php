<?php
Route::get('/', 'PageController@index');
Route::resource('stock', 'StockController'); //'stock' and 'stocks' can both be routed
Route::resource('stocks', 'StockController'); //'stock' and 'stocks' can both be routed
Route::resource('sectors', 'SectorController');
Route::get('index/{marketIndex}', 'StockController@index');
Route::get('topGainsLosses', 'PageController@topGainsLosses');

Route::group(['prefix' => 'ajax'], function(){
	Route::get('stock/currentPrice/{stockCode}', 'StockController@getCurrentPrice');
	Route::get('stock/dayChange/{stockCode}', 'StockController@getDayChange');
	Route::get('stocks/highestVolume', 'StockController@highestVolume');
	Route::get('stocks/{marketIndex}', 'StockController@stocks');
	Route::get('relatedstocks/{stockCode}', 'StockController@relatedStocks');
	Route::get('sectors/topPerforming/{topOrBottom}', 'SectorController@topPerformingSectors');
	Route::get('sectors/{sectorName}/daychanges', 'SectorController@sectorDayChanges');
	Route::get('sectors/{sectorName}/otherstocksinsector', 'SectorController@otherStocksInSector');
	Route::get('/marketstatus','MarketController@status');
	Route::get('/marketchange', 'MarketController@change');

	Route::group(['prefix' => 'graph'], function(){
		Route::get('stock/{stockCode}/{timeFrame}/{dataType}', 'GraphController@stock');
		Route::get('sector/{sectorName}/{timeFrame}/{dataType}', 'GraphController@sector');
		Route::get('sectorPie/{numberOfSectors}', 'GraphController@sectorCapsPieChart');
		Route::get('sectors/stocksInSectorPieChart/{sectorName}/{numberOfStocks}', 'GraphController@stocksInSectorPieChart');
	});
});

Route::group(['prefix' => 'dashboard'], function(){
	Route::get('/discontinued', 'PageController@discontinued');
	Route::get('/marketCapAdjustments', 'PageController@marketCapAdjustments');
});


Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

/*route::get('/test', function(){
});
*/