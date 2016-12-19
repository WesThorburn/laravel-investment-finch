<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\StockMetrics;
use Illuminate\Database\Eloquent\Model;

class Historicals extends Model
{
    protected $table = 'historicals';

    protected $guarded = ['id','created_at'];

    public function scopeDateCondition($query, $timeframe){
    	if($timeframe == 'last_month'){
    		return $query->where('date', '>', Carbon::now()->subMonth());
    	}
    	elseif($timeframe == 'last_3_months'){
    		return $query->where('date', '>', Carbon::now()->subMonths(3));
    	}
    	elseif($timeframe == 'last_6_months'){
    		return $query->where('date', '>', Carbon::now()->subMonths(6));
    	}
    	elseif($timeframe == 'last_year'){
    		return $query->where('date', '>', Carbon::now()->subYear());
    	}
    	elseif($timeframe == 'last_2_years'){
    		return $query->where('date', '>', Carbon::now()->subYears(2));
    	}
    	elseif($timeframe == 'last_5_years'){
    		return $query->where('date', '>', Carbon::now()->subYears(5));
    	}
    	elseif($timeframe == 'last_10_years'){
    		return $query->where('date', '>', Carbon::now()->subYears(10));
    	}
    	elseif($timeframe == 'all_time'){
    		return $query;
    	}
    }

    public static function getMovingAverage($stockCode, $timeframe){
        $recordsInTimeFrame = Historicals::where('stock_code', $stockCode)->orderBy('date', 'desc')->take($timeframe)->lists('close');
        if(count($recordsInTimeFrame) > 0){
            return $recordsInTimeFrame->sum()/$recordsInTimeFrame->count();
        }
        return null;
    }

    public static function getMostRecentHistoricalDate(){
        $date = Historicals::orderBy('date', 'desc')->take(1)->lists('date');
        if(isset($date[0])){
            return $date[0];
        }
        return null;
        
    }

    public static function getYesterdaysHistoricalsDate(){
        return Historicals::orderBy('date', 'desc')->distinct()->take(2)->lists('date')[1];
    }

    public static function getEMA($stockCode, $timeframe){
        $stockMetric = StockMetrics::where('stock_code', $stockCode)->first();
        $yesterdaysHistoricals = Historicals::where(['stock_code' => $stockCode, 'date' => Historicals::getMostRecentHistoricalDate()])->first();

        if($stockMetric && $yesterdaysHistoricals){
            $multiplier = (2/($timeframe + 1));

            if($timeframe == 12){
                return($stockMetric->last_trade - $yesterdaysHistoricals->twelve_day_ema) * $multiplier + $yesterdaysHistoricals->twelve_day_ema;
            }
            else if($timeframe == 26){
                return ($stockMetric->last_trade - $yesterdaysHistoricals->twenty_six_day_ema) * $multiplier + $yesterdaysHistoricals->twenty_six_day_ema;
            }
        }
        return 0;
    }

    public static function getSignalLine($stockCode, $mostRecentMACDValue){
        $previousDay = Historicals::where(['stock_code' => $stockCode, 'date' => Historicals::getMostRecentHistoricalDate()])->first();
        $nineDayMultiplier = (2 / (9 + 1));
        return ($mostRecentMACDValue - $previousDay->macd_line) * $nineDayMultiplier + $previousDay->macd_line;
    }

    public static function getStochasticK($stockCode, $timePeriod){
        $listOfHighs = Historicals::where('stock_code', $stockCode)
            ->orderBy('date', 'DESC')
            ->limit($timePeriod)
            ->lists('high');

        $listOfLows = Historicals::where('stock_code', $stockCode)
            ->orderBy('date', 'DESC')
            ->limit($timePeriod)
            ->lists('low');

        $stockMetrics = StockMetrics::where('stock_code', $stockCode)->first();

        if($listOfHighs->max() - $listOfLows->min() != 0){
            return ($stockMetrics->last_trade - $listOfLows->min())/($listOfHighs->max() - $listOfLows->min()) * 100;
        }
        return 0;
    }

    public static function getStochasticD($stockCode, $timePeriod){
        $kSMAs = Historicals::where('stock_code', $stockCode)
            ->orderBy('date', 'DESC')
            ->limit($timePeriod)
            ->lists('stochastic_k');

        if($kSMAs->count() > 0){
            return $kSMAs->sum()/$kSMAs->count();
        }
        return null;
    }

    public static function getOBV($stockCode){
        $stockMetrics = StockMetrics::where('stock_code', $stockCode)->first();

        $obv = 0;

        $yesterdaysHistoricals = Historicals::where(['stock_code' => $stockCode, 'date' => Historicals::getMostRecentHistoricalDate()])->first();

        if($stockMetrics->percent_change > 0){
            $obv = $yesterdaysHistoricals->obv + $stockMetrics->volume;
        }
        else if($stockMetrics->percent_change < 0){
            $obv = $yesterdaysHistoricals->obv - $stockMetrics->volume;
        }
        else if($stockMetrics->percent_change == 0){
            $obv = $yesterdaysHistoricals->obv;
        }
        return $obv;
    }

    public static function getRSI($stockCode){
        $stockMetrics = StockMetrics::where('stock_code', $stockCode)->first();

        $fiveDayChanges = Historicals::where('stock_code', $stockCode)
            ->where('date', '<=', Historicals::getMostRecentHistoricalDate())
            ->orderBy('date', 'DESC')
            ->limit(5)
            ->lists('day_change');

        $fiveDayGains = [];
        $fiveDayLosses = [];

        foreach($fiveDayChanges as $dayChange){
            if($dayChange > 0){
                array_push($fiveDayGains, abs($dayChange));
            }
            else if($dayChange < 0){
                array_push($fiveDayLosses, abs($dayChange));
            }
        }

        if(count($fiveDayGains) > 0){
            $averageGain = array_sum($fiveDayGains)/count($fiveDayGains);
        }
        else{
            $averageGain = 0;
        }
        
        if(count($fiveDayLosses) > 0){
            $averageLoss = array_sum($fiveDayLosses)/count($fiveDayLosses);
        }
        else{
            $averageLoss = 0;
        }

        if($averageLoss != 0){
            return 100 - 100 / (1 + ($averageGain / $averageLoss));
        }
        else{
            return 100;
        }
    }

    public static function getCCI($stockCode, $typicalPrice){
        $typicalPriceRecords = Historicals::where('stock_code', $stockCode)
            ->where('date', '<', Historicals::getMostRecentHistoricalDate())
            ->orderBy('date', 'DESC')
            ->limit(20)
            ->lists('typical_price');

        //Calculate 20-Period SMA
        if($typicalPriceRecords->count() != 0){
            $typicalPriceSMA = $typicalPriceRecords->sum()/$typicalPriceRecords->count();
        }
        else{
            $typicalPriceSMA = $typicalPrice;
        }

        //Calculate Mean Deviation
        foreach($typicalPriceRecords as $price){
            $price = abs($price - $typicalPriceSMA);
        }
        $meanDeviation = $typicalPriceRecords->sum()/$typicalPriceRecords->count();

        if($meanDeviation != 0){
            return ($typicalPrice - $typicalPriceSMA)/(0.15 * $meanDeviation);
        }
    }
}
