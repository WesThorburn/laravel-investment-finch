<?php

namespace App\Models;

use Carbon\Carbon;
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

    public static function getMovingAverage($stockCode, $timeFrame){
        $recordsInTimeFrame = Historicals::where('stock_code', $stockCode)->orderBy('date', 'desc')->take($timeFrame)->lists('close');
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
}
