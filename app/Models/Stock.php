<?php namespace App\Models;

use App\Models\StockMetrics;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model {

	use SoftDeletes;

	protected $dates = ['deleted_at'];

	protected $table = 'stocks';

	protected $guarded = ['id', 'created_at'];

	public function metrics(){
		return $this->hasOne('App\Models\StockMetrics', 'stock_code', 'stock_code');
	}

	public function gains(){
		return $this->hasOne('App\Models\StockGains', 'stock_code', 'stock_code');
	}

	public function sector(){
		return $this->hasOne('App\Models\SectorIndexHistoricals', 'sector', 'sector');
	}

	public function portfolios(){
    	return $this->belongsToMany('App\Models\Portfolio');
    }

    public function watchlists(){
    	return $this->belongsToMany('App\Models\Watchlist');
    }

	public static function scopeWithMarketIndex($query, $marketIndex){
		switch ($marketIndex){
			case 'asx20':
				return $query->where('asx_20', 1);
				break;
			case 'asx50':
				return $query->where('asx_50', 1);
				break;
			case 'asx100':
				return $query->where('asx_100', 1);
				break;
			case 'asx200':
				return $query->where('asx_200', 1);
				break;
			case 'asx300':
				return $query->where('asx_300', 1);
				break;
			case 'allOrds':
				return $query->where('all_ords', 1);
				break;
		}
		return $query;
	}

	public static function getListOfSectors(){
		return \DB::table('stocks')->select(\DB::raw('DISTINCT sector'))->lists('sector');
	}

	public static function getListOfIndexes(){
		return [
			'asx20',
			'asx50',
			'asx100',
			'asx200',
			'asx300',
			'allOrds'
		];
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
			$individualMarketCap = StockMetrics::where('stock_code', $stockCode)->pluck('current_market_cap');
			$relatedStocks = StockMetrics::whereIn('stock_code', $otherStocksInSector)
				->where('stock_code', '!=', $stockCode)
				->where('current_market_cap', '<=', ($individualMarketCap*10))
				->where('current_market_cap', '>=', ($individualMarketCap/10))
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
			array_push($graphData, array(getShortDateFromDate($record->date), $price, $fiftyDayMovingAverage, $twoHundredDayMovingAverage));
		}
		//Add Current day's trade value to graph data 
		//10:32am allows time for the metrics to be populated
		if(isTradingDay() 
			&& getCurrentTimeIntVal() >= 103200
			&& !Historicals::where(['stock_code' => $stockCode, 'date' => date('Y-m-d')])->first()){
			$stockMetric = StockMetrics::where('stock_code', $stockCode)->first();
			$metricDate = explode(" ", $stockMetric->updated_at)[0];
			array_push($graphData, array(getShortDateFromDate($record->date), $stockMetric->last_trade));
		}
		return $graphData;
	}

	public static function formatMarketIndex($index){
		switch ($index){
			case 'all':
				return "All ASX Stocks";
				break;
			case 'asx20':
				return "ASX 20 | Top 20 Stocks";
				break;
			case 'asx50':
				return "ASX 50 | Top 50 Stocks";
				break;
			case 'asx100':
				return "ASX 100 | Top 100 Stocks";
				break;
			case 'asx200':
				return "ASX 200 | Top 200 Stocks";
				break;
			case 'asx300':
				return "ASX 300 | Top 300 Stocks";
				break;
			case 'allOrds':
				return "All Ords | All Ordinaries";
				break;
		}
	}
	
}
