<?php

namespace App\Console\Commands;

use App\Models\SectorHistoricals;
use App\Models\Stock;
use App\Models\StockMetrics;

use Illuminate\Console\Command;

class UpdateSectorMetricsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:updateSectorMetrics {--testMode=false}{--mode=full}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the metrics for each sector. --mode=partial includes only Market Cap --mode=full includes all metrics';

    private $sectorMetrics = [
            'average_daily_volume', 
            'EBITDA', 
            'earnings_per_share_current', 
            'earnings_per_share_next_year', 
            'price_to_earnings', 
            'price_to_book', 
            'dividend_yield'];
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Updating ".$this->option('mode')." sector metrics. This may take several minutes...");

        $listOfSectors = Stock::getListOfSectors();
        array_push($listOfSectors, 'All');

        if($this->option('testMode') == 'true'){
            $this->info("Running in test mode. Only Banks and Telcos will be updated.");
            $listOfSectors = ['Banks', 'Telecommunication Services'];
        }

        foreach($listOfSectors as $sectorName){
            $stocksInSector = Stock::where('sector', $sectorName)->lists('stock_code');
            if($sectorName ==  'All'){
                $stocksInSector = Stock::lists('stock_code');
            }
            if(count($stocksInSector) > 0){
                $totalSectorMarketCap = UpdateSectorMetricsCommand::getTotalSectorMarketCap($stocksInSector);
                if(isTradingDay()){
                    SectorHistoricals::updateOrCreate(
                        [
                            'sector' => $sectorName,
                            'date' => date("Y-m-d")
                        ], 
                        [
                            'sector' => $sectorName,
                            'date' => date("Y-m-d"),
                            'total_sector_market_cap' => $totalSectorMarketCap,
                            'day_change' => round(UpdateSectorMetricsCommand::getSectorPercentChange($sectorName, $stocksInSector), 2),
                            'average_sector_market_cap' => $totalSectorMarketCap/count($stocksInSector)
                        ]
                    );

                    if($this->option('mode') == 'full'){
                        foreach($this->sectorMetrics as $metricName){
                            SectorHistoricals::updateOrCreate(
                                [
                                    'sector' => $sectorName,
                                    'date' => date("Y-m-d")
                                ], 
                                [
                                    $metricName => round(UpdateSectorMetricsCommand::getAverageMetric($metricName, $stocksInSector, $sectorName), 2),
                                ]
                            );
                        }
                    }
                }
            }
        }
    }

    public static function getAverageMetric($metricName, $listOfStocks, $sectorName){
        $sectorMetrics = array();
        foreach($listOfStocks as $stock){
            $sectorMetric = StockMetrics::where('stock_code', $stock)->pluck($metricName);
            array_push($sectorMetrics, $sectorMetric);
        }
        return array_sum($sectorMetrics)/count($sectorMetrics);
    }

    private function getSectorPercentChange($sectorName, $stocksInSector){
        $yesterdaysSectorHistoricalsDate = SectorHistoricals::getYesterdaysSectorHistoricalsDate();
        $yesterdaysTotalMarketCap = SectorHistoricals::where(['date' => $yesterdaysSectorHistoricalsDate, 'sector' => $sectorName])->pluck('total_sector_market_cap');

        if($yesterdaysTotalMarketCap > 0){
            return (100/$yesterdaysTotalMarketCap)*UpdateSectorMetricsCommand::getSectorTotalChange($stocksInSector);
        }
        return 0;
    }

    private function getSectorTotalChange($stocksInSector){
        $marketCapDayChanges = array();
        foreach($stocksInSector as $stock){
            $metric = StockMetrics::where('stock_code', $stock)->first();
            array_push($marketCapDayChanges, $metric->market_cap - ($metric->market_cap/(($metric->day_change/100)+1)));
        }
        return array_sum($marketCapDayChanges);
    }

    private function getTotalSectorMarketCap($stocksInSector){
        return StockMetrics::whereIn('stock_code', $stocksInSector)->sum('market_cap');
    }
}