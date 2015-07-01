<?php

use App\Models\Historicals;
use App\Models\Stock;

Route::get('/', 'SearchController@show');
Route::resource('search', 'SearchController');

Route::get('graph/{dataType}/{stockCode}/{timeFrame}', 'StockController@graph');
Route::resource('stock', 'StockController');

Route::get('/test', function(){
	date_default_timezone_set("Australia/Sydney");
	//Limit of 100 at a time due to yahoo's url length limit
	$datesNotAvailable = Historicals::distinct()->where('date',date("Y-m-d"))->lists('stock_code');
	foreach($datesNotAvailable as $naStockCode){
		echo "<br>".$naStockCode;
	}
	echo "<br><br>";

	$stockCodeList = Stock::whereNotIn('stock_code', $datesNotAvailable)->take(100)->lists('stock_code');
	$stockCodeParameter = "";
	foreach($stockCodeList as $stockCode){
		$stockCodeParameter .= "+".$stockCode.".AX";
	}
	dd($stockCodeParameter);
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);