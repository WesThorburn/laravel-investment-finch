<?php

namespace App\Console\Commands;

use App\Models\SectorIndexHistoricals;
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
            'volume', 
            'EBITDA', 
            'earnings_per_share_current', 
            'earnings_per_share_next_year', 
            'price_to_earnings', 
            'price_to_book',
            'peg_ratio',
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
                $totalSectorMarketCap = SectorIndexHistoricals::getTotalSectorMarketCap($stocksInSector);

                //Record Market cap in administration table if market cap is over 2 Trillion
                if($totalSectorMarketCap > 2000000){
                    \DB::table('administration')
                        ->where('option_name', 'Market Cap Error')
                        ->update([
                            'option_value' => 'Problem with '.$sectorName.' Sector. Total Sector Cap is: '.number_format($totalSectorMarketCap).'B.',
                            'updated_at' => date("Y-m-d H:i:s")
                        ]);
                }
                
                if(isTradingDay()){
                    SectorIndexHistoricals::updateOrCreate(
                        [
                            'sector' => $sectorName,
                            'date' => date("Y-m-d")
                        ], 
                        [
                            'sector' => $sectorName,
                            'date' => date("Y-m-d"),
                            'total_sector_market_cap' => $totalSectorMarketCap,
                            'day_change' => round(SectorIndexHistoricals::getSectorPercentChange($sectorName, $stocksInSector), 2),
                            'average_sector_market_cap' => $totalSectorMarketCap/count($stocksInSector)
                        ]
                    );

                    if($this->option('mode') == 'full'){
                        foreach($this->sectorMetrics as $metricName){
                            SectorIndexHistoricals::updateOrCreate(
                                [
                                    'sector' => $sectorName,
                                    'date' => date("Y-m-d")
                                ], 
                                [
                                    $metricName => round(StockMetrics::getAverageMetric($metricName, $stocksInSector, $sectorName), 2),
                                ]
                            );
                        }
                    }
                }
            }
        }
    }
}