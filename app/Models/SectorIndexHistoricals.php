<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\StockMetrics;

class SectorIndexHistoricals extends Model
{
    protected $table = 'sector_index_historicals';

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

    public function stock(){
        return $this->belongsTo('App\Models\Stock', 'sector', 'sector');
    }

    public static function getBestPerformingSector(){
        $bestPerformingSector = SectorIndexHistoricals::where('date', SectorIndexHistoricals::getMostRecentSectorIndexHistoricalsDate())
            ->where('stock_index', 0)
            ->orderBy('day_change', 'desc')
            ->take(1)
            ->lists('sector');
        return $bestPerformingSector[0];
    }

    public static function getSectorDayChanges($section, $limit = 5){
        if($section == 'top'){
            $order = "desc";
        }
        elseif($section == 'bottom'){
            $order = "asc";
        }
    	return SectorIndexHistoricals::where('date', SectorIndexHistoricals::getMostRecentSectorIndexHistoricalsDate())
            ->where('stock_index', 0)
            ->where('sector', '!=', 'Class Pend')
            ->where('sector', '!=', 'Not Applic')
            ->where('sector', '!=', 'All')
            ->orderBy('day_change', $order)
            ->take($limit)
            ->get();
    }

    public static function getSelectedSectorDayChange($sectorName){
        return SectorIndexHistoricals::where('date', SectorIndexHistoricals::getMostRecentSectorIndexHistoricalsDate())
            ->where('sector', $sectorName)
            ->pluck('day_change');
    }

    public static function getSectorDayChangeTitle($section){
        $dayForTitle = SectorIndexHistoricals::getSectorWeekDay();
        if($section == 'top'){
            return $dayForTitle."'s Best Performing Sectors";
        }
        elseif($section == 'bottom'){
            return $dayForTitle."'s Worst Performing Sectors";
        }
    }

    public static function getSectorWeekDay(){
        $mostRecentSectorIndexHistoricalsDate = SectorIndexHistoricals::getMostRecentSectorIndexHistoricalsDate();
        if($mostRecentSectorIndexHistoricalsDate == date("Y-m-d")){
            //Most Recent Date is today
            return "Today";
        }
        else{
            return date("l", strtotime($mostRecentSectorIndexHistoricalsDate));
        }
    }

    public static function getMarketChange(){
        return SectorIndexHistoricals::where('date', SectorIndexHistoricals::getMostRecentSectorIndexHistoricalsDate())
            ->where('sector', 'All')
            ->pluck('day_change');
    }

    public static function getMarketChangeMessage(){
        $mostRecentSectorIndexHistoricalsDate = SectorIndexHistoricals::getMostRecentSectorIndexHistoricalsDate();
        $marketChange = SectorIndexHistoricals::getMarketChange();
        if($mostRecentSectorIndexHistoricalsDate == date("Y-m-d")){
            if($marketChange < 0){
                return "The ASX is down ".$marketChange."% today.";
            }
            elseif($marketChange >= 0){
                return "The ASX is up ".$marketChange."% today.";
            }
        }
        else{
            $dayForTitle = date("l", strtotime($mostRecentSectorIndexHistoricalsDate));
            if($marketChange < 0){
                return "The ASX was down ".$marketChange."% on ".$dayForTitle.".";
            }
            elseif($marketChange >= 0){
                return "The ASX was up ".$marketChange."% on ".$dayForTitle.".";
            }
        }
    }

    public static function getMostRecentSectorIndexHistoricalsDate(){
        return SectorIndexHistoricals::orderBy('date', 'desc')->take(1)->lists('date')[0];
    }

    public static function getYesterdaysSectorIndexHistoricalsDate(){
        return SectorIndexHistoricals::orderBy('date', 'desc')->distinct()->take(2)->lists('date')[1];
    }

    public static function getAllSectorGraphData($sectorLimit){
        $graphData = array();
        $mostRecentDate = SectorIndexHistoricals::getMostRecentSectorIndexHistoricalsDate();
        $sectors = SectorIndexHistoricals::where('date', $mostRecentDate)
            ->where('stock_index', 0)
            ->where('sector', '!=', 'All')
            ->orderBy('total_sector_market_cap', 'DESC')
            ->limit(SectorIndexHistoricals::sectorLimitToNumber($sectorLimit))
            ->get();
        $allSectorsMarketCap = SectorIndexHistoricals::where(['date' => $mostRecentDate, 'sector' => 'All'])->pluck('total_sector_market_cap');
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
        return $graphData;
    }

    public static function getIndividualSectorGraphData($sectorName, $timeFrame = 'last_month', $dataType){
        $graphData = array();
        $historicals = SectorIndexHistoricals::where(['sector' => htmlspecialchars_decode($sectorName)])->dateCondition($timeFrame)->orderBy('date')->get();
        foreach($historicals as $record){
            if($dataType == 'Market Cap'){
                $recordValue = $record->total_sector_market_cap;
            }
            elseif($dataType == 'Volume'){
                $recordValue = $record->volume;
            }
            array_push($graphData, array(getCarbonDateFromDate($record->date)->toFormattedDateString(), $recordValue));
        }
        return $graphData;
    }

    public static function getSectorPercentChange($sectorName, $stocksInSector){
        $yesterdaysSectorIndexHistoricalsDate = SectorIndexHistoricals::getYesterdaysSectorIndexHistoricalsDate();
        $yesterdaysTotalMarketCap = SectorIndexHistoricals::where(['date' => $yesterdaysSectorIndexHistoricalsDate, 'sector' => $sectorName])->pluck('total_sector_market_cap');

        if($yesterdaysTotalMarketCap > 0){
            return (100/$yesterdaysTotalMarketCap)*SectorIndexHistoricals::getSectorTotalChange($stocksInSector);
        }
        return 0;
    }

    public static function getSectorTotalChange($stocksInSector){
        $marketCapDayChanges = array();
        foreach($stocksInSector as $stock){
            $metric = StockMetrics::where('stock_code', $stock)->first();
            array_push($marketCapDayChanges, $metric->market_cap - ($metric->market_cap/(($metric->percent_change/100)+1)));
        }
        return array_sum($marketCapDayChanges);
    }

    public static function getTotalSectorMarketCap($stocksInSector){
        return StockMetrics::whereIn('stock_code', $stocksInSector)->sum('market_cap');
    }

    private static function sectorLimitToNumber($sectorLimit){
        if($sectorLimit == 'all'){
            $mostRecentDate = SectorIndexHistoricals::getMostRecentSectorIndexHistoricalsDate();
            return SectorIndexHistoricals::where('date', $mostRecentDate)
                ->where('stock_index', 0)
                ->where('sector', '!=', 'All')
                ->lists('sector')
                ->count();
        }
        else{
            $explodedSectorLimit = explode('_', $sectorLimit);
            return end($explodedSectorLimit);
        }
    }
}
