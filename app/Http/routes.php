<?php

Route::get('/', 'SearchController@show');
Route::resource('search', 'SearchController');

Route::get('graph/{dataType}/{stockCode}/{timeFrame}', 'StockController@graph');
Route::resource('stock', 'StockController');

Route::get('/test', function(){
	echo "http://real-chart.finance.yahoo.com/table.csv?s=BGL.AX&d=".(date('m')-1)."&e=".date('d')."&f=".date('Y')."&g=d&a=".(date('m')-1)."&b=".date('d')."&c=".date('Y')."&ignore=.csv";
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);