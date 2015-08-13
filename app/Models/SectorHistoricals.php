<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SectorHistoricals extends Model
{
    protected $table = 'sector_historicals';

    protected $fillable = [
    	'sector',
    	'date',
        'total_sector_market_cap',
    	'day_change',
        'average_daily_volume',
        'EBITDA',
        'earnings_per_share_current',
        'earnings_per_share_next_year',
        'price_to_earnings',
        'price_to_book',
        'dividend_yield',
        'average_sector_market_cap',
    	'created_at',
    	'updated_at'
    ];

    public function stock(){
        return $this->belongsTo('App\Models\Stock', 'sector', 'sector');
    }

    public static function getBestPerformingSector(){
        $bestPerformingSector = SectorHistoricals::where('date', SectorHistoricals::getMostRecentSectorHistoricalsDate())
            ->orderBy('day_change', 'desc')
            ->take(1)
            ->lists('sector');
        return $bestPerformingSector[0];
    }

    public static function getSectorDayChanges($section, $limit = 5){
        if($section == 'sectorDayGain'){
            $order = "desc";
        }
        elseif($section == 'sectorDayLoss'){
            $order = "asc";
        }
    	return SectorHistoricals::where('date', SectorHistoricals::getMostRecentSectorHistoricalsDate())
            ->where('sector', '!=', 'Class Pend')
            ->where('sector', '!=', 'Not Applic')
            ->where('sector', '!=', 'All')
            ->orderBy('day_change', $order)
            ->take($limit)
            ->get();
    }

    public static function getSelectedSectorDayChange($sectorName){
        return SectorHistoricals::where('date', SectorHistoricals::getMostRecentSectorHistoricalsDate())
            ->where('sector', $sectorName)
            ->pluck('day_change');
    }

    public static function getSectorDayChangeTitle($section){
        $dayForTitle = SectorHistoricals::getSectorWeekDay();
        if($section == 'sectorDayGain'){
            return $dayForTitle."'s Best Performing Sectors";
        }
        elseif($section == 'sectorDayLoss'){
            return $dayForTitle."'s Worst Performing Sectors";
        }
    }

    public static function getSectorWeekDay(){
        $mostRecentSectorHistoricalsDate = SectorHistoricals::getMostRecentSectorHistoricalsDate();
        if($mostRecentSectorHistoricalsDate == date("Y-m-d")){
            //Most Recent Date is today
            return "Today";
        }
        else{
            return date("l", strtotime($mostRecentSectorHistoricalsDate));
        }
    }

    public static function getMarketChange(){
        return SectorHistoricals::where('date', SectorHistoricals::getMostRecentSectorHistoricalsDate())
            ->where('sector', 'All')
            ->pluck('day_change');
    }

    public static function getMarketChangeMessage(){
        $mostRecentSectorHistoricalsDate = SectorHistoricals::getMostRecentSectorHistoricalsDate();
        $marketChange = SectorHistoricals::getMarketChange();
        if($mostRecentSectorHistoricalsDate == date("Y-m-d")){
            if($marketChange < 0){
                return "The ASX is down ".$marketChange."% today.";
            }
            elseif($marketChange >= 0){
                return "The ASX is up ".$marketChange."% today.";
            }
        }
        else{
            $dayForTitle = date("l", strtotime($mostRecentSectorHistoricalsDate));
            if($marketChange < 0){
                return "The ASX was down ".$marketChange."% on ".$dayForTitle.".";
            }
            elseif($marketChange >= 0){
                return "The ASX was up ".$marketChange."% on ".$dayForTitle.".";
            }
        }
    }

    public static function getMostRecentSectorHistoricalsDate(){
        return SectorHistoricals::orderBy('date', 'desc')->take(1)->lists('date')[0];
    }
}
