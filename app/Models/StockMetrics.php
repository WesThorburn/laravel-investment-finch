<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMetrics extends Model {

	protected $table = 'stock_metrics';

	protected $fillable = [
		"stock_code",
		"last_trade",
		"day_change",
		"volume",
		"EBITDA",
		"earnings_per_share_current",
		"earnings_per_share_next_year",
		"price_to_earnings",
		"price_to_sales",
		"price_to_book",
		"year_high",
		"year_low",
		"fifty_day_moving_average",
		"two_hundred_day_moving_average",
		"market_cap",
		"dividend_yield",
		"trend_short_term",
		"trend_medium_term",
		"trend_long_term",
		"updated_at"
	];

	public function stock(){
		return $this->belongsTo('App\Models\Stock', 'stock_code', 'stock_code');
	}

	public function scopeOmitOutliers($query, $omitCondition = 'omit'){
		if($omitCondition == 'omit'){
			return $query->where('last_trade', '>=', '0.05')
				->where('volume', '!=', 0)
				->where('earnings_per_share_current', '>=', 0.01)
				->where('price_to_earnings', '>=', 0.01)
				->where('price_to_book', '>=', 0.01)
				->where('market_cap', '!=', 'N/A');
		}
		return $query;
	}

	public function scopeLimit($query, $limit){
		if($limit == 'top_5'){
			return $query->take(5);
		}
		elseif($limit == 'top_10'){
			return $query->take(10);
		}
		elseif($limit == 'top_15'){
			return $query->take(15);
		}
		elseif($limit == 'top_20'){
			return $query->take(20);
		}
		elseif($limit == 'all'){
			return $query;
		}
	}

	public static function getMetricsByStockList($listOfStocks, $omitCondition){
		return StockMetrics::whereIn('stock_code', $listOfStocks)->omitOutliers($omitCondition)->with('stock')->get();
	}

	public static function getAverageMetric($metricName, $listOfStocks, $sectorName){
        $sectorMetrics = array();
        foreach($listOfStocks as $stock){
            $sectorMetric = StockMetrics::where('stock_code', $stock)->pluck($metricName);
            array_push($sectorMetrics, $sectorMetric);
        }
        return array_sum($sectorMetrics)/count($sectorMetrics);
    }

    public static function getMarketCapsInSectorGraphData($sectorName, $numberOfStocks){
    	$graphData = array();
    	$stocksInSector = Stock::where('sector', htmlspecialchars_decode($sectorName))->lists('stock_code');
    	$marketCaps = StockMetrics::with('stock')->select('stock_code','market_cap')->whereIn('stock_code', $stocksInSector)->orderBy('market_cap', 'DESC')->limit($numberOfStocks)->get();
    	$sumOfMarketCaps = $marketCaps->sum('market_cap');
    	foreach($marketCaps as $stock){
    		if($sumOfMarketCaps > 0 && $stock->market_cap > 0){
    			$percentageShare = 100/$sumOfMarketCaps * $stock->market_cap;
    		}
    		else{
    			$percentageShare = 0;
    		}
    		array_push($graphData, array($stock->stock->company_name, round($percentageShare, 2)));
    	}
    	return $graphData;
    }

    private function generateAnalysis(StockMetrics $stockMetrics){
        return $stockMetrics->stock->company_name;
    }
}
