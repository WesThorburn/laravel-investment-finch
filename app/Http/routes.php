<?php

Route::get('/', 'SearchController@show');
Route::resource('search', 'SearchController');
Route::resource('stock', 'StockController');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);