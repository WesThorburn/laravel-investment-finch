<?php

Route::get('/', 'SearchController@show');
Route::resource('search', 'SearchController');

Route::get('graph/{stockCode}/{timeFrame}/{dataType}', 'StockController@graph');
Route::get('relatedstocks/{stockCode}', 'StockController@relatedStocks');
Route::resource('stock', 'StockController');

Route::get('/servertime', function(){
	return "<b>Sydney Time: ".date('F j, Y, g:i a');
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);