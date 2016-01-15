<?php
Route::get('/', 'PageController@index');
Route::get('/topGainsLosses', 'PageController@topGainsLosses');

//Allows both 'stock' and 'stocks' to be in the URL
Route::resource('stock', 'StockController');
Route::resource('stocks', 'StockController');

Route::resource('search', 'SearchController');
Route::resource('sectors', 'SectorController');

Route::get('stockGraph/{stockCode}/{timeFrame}/{dataType}', 'GraphController@stock');
Route::get('sectorGraph/{sectorName}/{timeFrame}/{dataType}', 'GraphController@sector');
Route::get('sectorCapsPieChart/{numberOfSectors}', 'GraphController@sectorCapsPieChart');

Route::group(['prefix' => 'ajax'], function(){
	Route::get('currentPrice/{stockCode}', 'StockController@getCurrentPrice');
	Route::get('dayChange/{stockCode}', 'StockController@getDayChange');
	Route::get('stocks', 'StockController@stocks');
	Route::get('relatedstocks/{stockCode}', 'StockController@relatedStocks');
	Route::get('sectors/{sectorName}/daychanges', 'SectorController@sectorDayChanges');
	Route::get('sectors/{sectorName}/otherstocksinsector', 'SectorController@otherStocksInSector');
	Route::get('/marketstatus','MarketController@status');
	Route::get('/marketchange', 'MarketController@change');
});

/*route::get('/test', function(){
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);*/