<?php namespace App\Models;

use App\Models\StockMetrics;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model {

	protected $table = 'stocks';

	protected $fillable = [
		'stock_code',
		'company_name',
		'sector',
		'business_summary'
	];

	public function metrics(){
		return $this->hasOne('App\Models\StockMetrics', 'stock_code', 'stock_code');
	}

	public function gains(){
		return $this->hasOne('App\Models\StockGains', 'stock_code', 'stock_code');
	}

	public function sector(){
		return $this->hasOne('App\Models\SectorHistoricals', 'sector', 'sector');
	}

	public static function getListOfSectors(){
		return \DB::table('stocks')->select(\DB::raw('DISTINCT sector'))->lists('sector');
	}

	public static function getSectorDropdown(){
		$sectorDropdown = array("All" => "All");
		foreach(Stock::getListOfSectors() as $key => $sector){
			$sectorDropdown[$sector] = $sector;
		}
		return $sectorDropdown;
	}

	public static function getRelatedStocks($stockCode){
		$otherStocksInSector = Stock::where('sector', Stock::where('stock_code', $stockCode)->pluck('sector'))->lists('stock_code');
		if(count($otherStocksInSector) > 10){
			$individualMarketCap = StockMetrics::where('stock_code', $stockCode)->pluck('market_cap');
			$relatedStocks = StockMetrics::whereIn('stock_code', $otherStocksInSector)
				->where('stock_code', '!=', $stockCode)
				->where('market_cap', '<=', ($individualMarketCap*10))
				->where('market_cap', '>=', ($individualMarketCap/10))
				->lists('stock_code');
				
			//If Mkt Cap conditions leave too few left, just return $otherStocksInSector
			if(count($relatedStocks) < 5){
				return $otherStocksInSector;
			}
			return $relatedStocks;
		}
		return $otherStocksInSector;
	}

	public static function getGraphData($stockCode, $timeFrame = 'last_month', $dataType){
		$historicals = Historicals::where(['stock_code' => $stockCode])->dateCondition($timeFrame)->orderBy('date')->get();
		$graphData = array();
		foreach($historicals as $record){
			if($dataType == 'Price'){
				$price = $record->close;
				$fiftyDayMovingAverage = $record->fifty_day_moving_average;
				$twoHundredDayMovingAverage = $record->two_hundred_day_moving_average;
			}
			array_push($graphData, array(getCarbonDateFromDate($record->date)->toFormattedDateString(), $price, $fiftyDayMovingAverage, $twoHundredDayMovingAverage));
		}
		//Add Current day's trade value to graph data 
		//10:32am allows time for the metrics to be populated
		if(isTradingDay() 
			&& getCurrentTimeIntVal() >= 103200
			&& !Historicals::where(['stock_code' => $stockCode, 'date' => date('Y-m-d')])->first()){
			$stockMetric = StockMetrics::where('stock_code', $stockCode)->first();
			$metricDate = explode(" ", $stockMetric->updated_at)[0];
			array_push($graphData, array(getCarbonDateFromDate($metricDate)->toFormattedDateString(), $stockMetric->last_trade));
		}
		return $graphData;
	}
	
}
