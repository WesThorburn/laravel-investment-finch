<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Historicals extends Model
{
    protected $table = 'historicals';

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
}
