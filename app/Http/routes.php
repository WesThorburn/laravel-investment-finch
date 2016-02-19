<?php
Route::get('/', 'PageController@index');
Route::get('/dashboard', 'PageController@dashboard');
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

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

use App\Models\StockMetrics;

route::get('/test', function(){
	$stocksWithIncorrectMarketCaps = ["URF","MOV","TIX","NSR","PGF","FGG","PAI","CQA","BPA","IDR","CMA","WAX","FGX","TOF","EMF","USG","BAF","UPD","KLO","SAO","EAI","USF","WDE","WMK","GDF","BIQ","AYZ","ENC","AHJ","BWR","AYK","AIK","APW","AYD","AWQ","PAF","RYD","UPG","TOT","IIL","AYH","FSI","8EC","VGI","TML","SCG","GC1","AOD","KLR","MKE","AAI","KFG","AIQ","AUP","FDC","PTX","DTX","USR","AKY","EOR","BOP","AIB","SXI","SLE","NTL","EGP","MFE","MUB","OGH","ELR","OEG","DAF","EQU","ASN","SXS","SZG","RCF","AQJ","PRH","OOK","AYJ","POW", "IVQ", "CR8", "CGW", "LVT", "TV2", "HML", "WNR"];
	foreach($stocksWithIncorrectMarketCaps as $stock){
		$metric = StockMetrics::where('stock_code', $stock)->first();
		if($metric){
			$metric->market_cap_requires_adjustment = 1;
			$metric->save();
		}
	}
});
