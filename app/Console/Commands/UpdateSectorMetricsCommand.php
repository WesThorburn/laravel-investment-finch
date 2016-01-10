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
        if($this->option('testMode') == 'true'){
            $this->info("[Test Mode]");
            foreach(['Banks', 'Telecommunication Services'] as $sector){
                $stocksInSector = Stock::where('sector', $sector)->lists('stock_code');
                UpdateSectorMetricsCommand::calculateSectorDayGain($stocksInSector, $sector);
            }
            $this->info('Banks & Telecommunication Services sectors updated.');
        }
        else{
            $this->info("Updating sector metrics...");
            $this->info("Mode: ".$this->option('mode'));
            $sectors = \DB::table('stocks')->select(\DB::raw('DISTINCT sector'))->lists('sector');

            foreach($sectors as $sector){
                $stocksInSector = Stock::where('sector', $sector)->lists('stock_code');
                UpdateSectorMetricsCommand::calculateSectorDayGain($stocksInSector, $sector);

                if($this->option('mode') == 'full'){
                    $sectorMetrics = [
                        'average_daily_volume', 
                        'EBITDA', 
                        'earnings_per_share_current', 
                        'earnings_per_share_next_year', 
                        'price_to_earnings', 
                        'price_to_book', 
                        'dividend_yield'
                    ];
                    foreach($sectorMetrics as $sectorMetric){
                        UpdateSectorMetricsCommand::calculateMetric($sectorMetric, $stocksInSector, $sector);
                    }
                }
            }
            //Calculate change for whole market
            $allStockCodes = Stock::lists('stock_code');
            UpdateSectorMetricsCommand::calculateSectorDayGain($allStockCodes, "All");
            $this->info("Sector day changes have been updated!");
        }
    }

    private static function calculateSectorDayGain($listOfStocks, $sectorName){
        $marketCaps = array();
        $marketCapDayChanges = array();
        foreach($listOfStocks as $stock){
            $marketCap = StockMetrics::where('stock_code', $stock)->pluck('market_cap');
            $dayChange = StockMetrics::where('stock_code', $stock)->pluck('day_change');
            array_push($marketCaps, $marketCap);
            array_push($marketCapDayChanges, $marketCap - ($marketCap/(($dayChange/100)+1)));
        }

        //Calculate Market Cap % Change
        $mostRecentSectorHistoricalsDate = SectorHistoricals::getMostRecentSectorHistoricalsDate();
        $yesterdaysTotalMarketCap = SectorHistoricals::where(['date' => $mostRecentSectorHistoricalsDate, 'sector' => 'All'])->pluck('total_sector_market_cap');

        $totalSectorDayChange = array_sum($marketCapDayChanges);
        if($yesterdaysTotalMarketCap > 0){
            $percentChange = (100/$yesterdaysTotalMarketCap)*$totalSectorDayChange;
        }
        else{
            $percentChange = 0;
        }

        //Calculate Sector's Average Market Cap
        $totalSectorMarketCaps = array_sum($marketCaps);
        $numberOfMarketCaps = count($marketCaps);
        if($numberOfMarketCaps > 0){
            $averageSectorMarketCap = $totalSectorMarketCaps/$numberOfMarketCaps;
        }
        else{
            $averageSectorMarketCap = 0;
        }
        
        if(isTradingDay()){
            SectorHistoricals::updateOrCreate(
                [
                    'sector' => $sectorName,
                    'date' => date("Y-m-d")
                ], 
                [
                    'sector' => $sectorName,
                    'date' => date("Y-m-d"),
                    'total_sector_market_cap' => $totalSectorMarketCaps,
                    'day_change' => round($percentChange, 2),
                    'average_sector_market_cap' => $averageSectorMarketCap
                ]
            );
        }
    }

    public static function calculateMetric($metricName, $listOfStocks, $sectorName){
        $sectorMetrics = array();
        foreach($listOfStocks as $stock){
            $sectorMetric = StockMetrics::where('stock_code', $stock)->pluck($metricName);
            array_push($sectorMetrics, $sectorMetric);
        }
        $numberOfSectorMetrics = count($sectorMetrics);
        if($numberOfSectorMetrics > 0){
            $averageSectorMetricValue = array_sum($sectorMetrics)/$numberOfSectorMetrics;
        }
        else{
            $averageSectorMetricValue = 0;
        }

        if(isTradingDay()){
            SectorHistoricals::updateOrCreate(
                [
                    'sector' => $sectorName,
                    'date' => date("Y-m-d")
                ], 
                [
                  $metricName => round($averageSectorMetricValue, 2),
                ]
            );
        }
    }
}
