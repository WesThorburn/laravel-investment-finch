<?php

Route::get('/', 'SearchController@home');
Route::resource('search', 'SearchController');
Route::resource('stock', 'StockController');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);