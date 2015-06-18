<?php
use App\Models\Stock;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'SearchController@home');
Route::resource('search', 'SearchController');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

/*Route::get('history', function(){
	foreach(Stock::lists('stock_code') as $stockCode){
        $historicalSheetUrl = "http://real-chart.finance.yahoo.com/table.csv?s=".$stockCode.".AX&d=".date('m')."&e=".date('d')."&f=".date('Y')."&g=d&a=1&b=1&c=2000&ignore=.csv";
        if(get_headers($historicalSheetUrl, 1)[0] == 'HTTP/1.1 200 OK')
        {	
            $spreadSheet = trim(str_replace("Date,Open,High,Low,Close,Volume,Adj Close", "", file_get_contents($historicalSheetUrl)));
            $explodedSpreadsheet = explode('\r\n', $spreadSheet);
            dd($explodedSpreadsheet);
            foreach($spreadSheetRows as $row){
                //$this->info($row);
                
            }
        }
        break;
    }
});*/