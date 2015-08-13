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
    protected $signature = 'stocks:updateSectorMetrics {--testMode=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the metrics for each sector.';

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
            foreach(['Bank', 'Telecommunication Service'] as $sector){
                $stocksInSector = Stock::where('sector', $sector)->lists('stock_code');
                UpdateSectorMetricsCommand::calculateDayGain($stocksInSector, $sector);
            }
            $this->info('Bank & Telecommunication Service sectors updated.');
        }
        else{
            $this->info("Updating sector metrics...");
            $sectors = \DB::table('stocks')->select(\DB::raw('DISTINCT sector'))->lists('sector');
            foreach($sectors as $sector){
                $stocksInSector = Stock::where('sector', $sector)->lists('stock_code');
                UpdateSectorMetricsCommand::calculateDayGain($stocksInSector, $sector);
                //UpdateSectorMetricsCommand::calculateMetric('average_daily_volume', $stocksInSector, $sector);
            }
            //Calculate change for whole market
            $allStockCodes = Stock::lists('stock_code');
            UpdateSectorMetricsCommand::calculateDayGain($allStockCodes, "All");
            $this->info("Sector day changes have been updated!");
        }
    }

    private static function calculateDayGain($listOfStocks, $sectorName){
        $marketCaps = array();
        $marketCapDayChanges = array();
        foreach($listOfStocks as $stock){
            $marketCap = StockMetrics::where('stock_code', $stock)->pluck('market_cap');
            $dayChange = StockMetrics::where('stock_code', $stock)->pluck('day_change');
            array_push($marketCaps, $marketCap);
            array_push($marketCapDayChanges, $marketCap - ($marketCap/(($dayChange/100)+1)));
        }
        $totalSectorMarketCaps = array_sum($marketCaps);
        $totalSectorDayChange = array_sum($marketCapDayChanges);
        if($totalSectorMarketCaps > 0){
            $percentChange = (100/$totalSectorMarketCaps)*$totalSectorDayChange;
        }
        else{
            $percentChange = 0;
        }

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
/*                'average_daily_volume' => ,
                'EBITDA' => ,
                'earnings_per_share_current' => ,
                'earnings_per_share_next_year' => ,
                'price_to_earnings' => ,
                'price_to_book' => ,
                'dividend_yield' => ,*/
            ]
        );
    }

/*    public static function calculateMetric($metricName, $listOfStocks, $sectorName){
        foreach($listOfStocks as $stock){

        }
    }*/
}
