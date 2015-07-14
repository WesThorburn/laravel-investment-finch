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

    public static function getSectorDayChanges(){
    	return SectorHistoricals::where('date', date("Y-m-d"))->get();
    }
}
