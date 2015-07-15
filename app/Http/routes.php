<?php

Route::get('/', 'SearchController@show');
Route::resource('search', 'SearchController');

Route::get('graph/{stockCode}/{timeFrame}/{dataType}', 'StockController@graph');
Route::get('relatedstocks/{stockCode}', 'StockController@relatedStocks');
Route::resource('stock', 'StockController');

Route::get('/servertime', function(){
	return "<b>".date('l F j, Y, g:i a')." (Sydney)";
});

Route::get('/marketchange', 'SearchController@marketChange');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);