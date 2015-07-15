<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectorHistoricals extends Model
{
    protected $table = 'sector_historicals';

    protected $fillable = [
    	'sector',
    	'date',
    	'day_change',
    	'created_at',
    	'updated_at'
    ];

    public static function getSectorDayChanges($order){
    	return SectorHistoricals::where('date', date("Y-m-d"))
            ->where('sector', '!=', 'Class Pend')
            ->where('sector', '!=', 'All')
            ->orderBy('day_change', $order)
            ->take(5)
            ->get();
    }

    public static function getMarketChange(){
        return SectorHistoricals::where('date', date("Y-m-d"))
            ->where('sector', 'All')
            ->pluck('day_change');
    }
}
