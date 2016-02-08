<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Historicals extends Model
{
    protected $table = 'historicals';

    protected $fillable = [
        'stock_code',
        'date',
        'open',
        'high',
        'low',
        'close',
        'volume',
        'adj_close',
        'fifty_day_moving_average',
        'two_hundred_day_moving_average'
    ];

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

    public static function getMovingAverage($stockCode, $timeFrame){
        $recordsInTimeFrame = Historicals::where('stock_code', $stockCode)->orderBy('date', 'desc')->take($timeFrame)->lists('close');
        return $recordsInTimeFrame->sum()/$recordsInTimeFrame->count();
    }

    public static function getMostRecentHistoricalDate($stockCode){
        return Historicals::where('stock_code', $stockCode)->orderBy('date', 'desc')->take(1)->lists('date')[0];
    }

    public static function getYesterdaysHistoricalsDate(){
        return Historicals::orderBy('date', 'desc')->distinct()->take(2)->lists('date')[1];
    }
}
