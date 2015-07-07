<?php

use App\Models\Historicals;
use App\Models\Stock;

Route::get('/', 'SearchController@show');
Route::resource('search', 'SearchController');

Route::get('graph/{stockCode}/{timeFrame}', 'StockController@graph');
Route::resource('stock', 'StockController');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);