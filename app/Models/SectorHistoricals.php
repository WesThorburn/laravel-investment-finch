<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\StockMetrics;

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

    public static function getYesterdaysSectorHistoricalsDate(){
        return SectorHistoricals::orderBy('date', 'desc')->distinct()->take(2)->lists('date')[1];
    }

    public static function getGraphData($sectorName, $timeFrame = 'last_month', $dataType){
        $graphData = array();
        if($dataType == 'Individual Sectors'){
            $mostRecentDate = SectorHistoricals::getMostRecentSectorHistoricalsDate();
            $sectors = SectorHistoricals::where('date', $mostRecentDate)->get();
            $allSectorsMarketCap = SectorHistoricals::where(['date' => $mostRecentDate, 'sector' => 'All'])->pluck('total_sector_market_cap');
            foreach($sectors as $sector){
                if($sector->sector != 'All'){
                    if($allSectorsMarketCap > 0 && $sector->total_sector_market_cap > 0){
                        $sectorPercent = 100/$allSectorsMarketCap*$sector->total_sector_market_cap;
                    }
                    else{
                        $sectorPercent = 0;
                    }
                    array_push($graphData, array($sector->sector, round($sectorPercent, 2)));
                }
            }
        }
        else{
            $historicals = SectorHistoricals::where(['sector' => htmlspecialchars_decode($sectorName)])->dateCondition($timeFrame)->orderBy('date')->get();
            foreach($historicals as $record){
                if($dataType == 'Market Cap'){
                    $recordValue = $record->total_sector_market_cap;
                }
                elseif($dataType == 'Volume'){
                    $recordValue = $record->average_daily_volume;
                }
                array_push($graphData, array(getCarbonDateFromDate($record->date)->toFormattedDateString(), $recordValue));
            }
        }
        return $graphData;
    }

     public static function getSectorPercentChange($sectorName, $stocksInSector){
        $yesterdaysSectorHistoricalsDate = SectorHistoricals::getYesterdaysSectorHistoricalsDate();
        $yesterdaysTotalMarketCap = SectorHistoricals::where(['date' => $yesterdaysSectorHistoricalsDate, 'sector' => $sectorName])->pluck('total_sector_market_cap');

        if($yesterdaysTotalMarketCap > 0){
            return (100/$yesterdaysTotalMarketCap)*SectorHistoricals::getSectorTotalChange($stocksInSector);
        }
        return 0;
    }

    public static function getSectorTotalChange($stocksInSector){
        $marketCapDayChanges = array();
        foreach($stocksInSector as $stock){
            $metric = StockMetrics::where('stock_code', $stock)->first();
            array_push($marketCapDayChanges, $metric->market_cap - ($metric->market_cap/(($metric->day_change/100)+1)));
        }
        return array_sum($marketCapDayChanges);
    }

    public static function getTotalSectorMarketCap($stocksInSector){
        return StockMetrics::whereIn('stock_code', $stocksInSector)->sum('market_cap');
    }
}
