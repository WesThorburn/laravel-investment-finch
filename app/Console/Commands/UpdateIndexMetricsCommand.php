<?php

namespace App\Console\Commands;

use App\Models\SectorIndexHistoricals;
use App\Models\Stock;
use App\Models\StockMetrics;

use Illuminate\Console\Command;

class UpdateIndexMetricsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:updateIndexMetrics {--testMode=false}{--mode=full}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the metrics for all stock indexes --mode=partial includes only Market Cap --mode=full includes all metrics';

    private $indexMetrics = [
            'volume', 
            'EBITDA', 
            'earnings_per_share_current', 
            'earnings_per_share_next_year', 
            'price_to_earnings', 
            'price_to_book',
            'peg_ratio',
            'dividend_yield'
        ];
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
        $this->info("Updating ".$this->option('mode')." index metrics. This may take several minutes...");

        $listOfIndexes = Stock::getListOfIndexes();

        if($this->option('testMode') == 'true'){
            $this->info("Running in test mode. Only the ASX20 Index will be updated.");
            $listOfIndexes = ['asx20'];
        }

        foreach($listOfIndexes as $indexName){
            $stocksInIndex = Stock::withMarketIndex($indexName)->lists('stock_code');
            $totalIndexMarketCap = SectorIndexHistoricals::getTotalSectorMarketCap($stocksInIndex);
            if(isTradingDay()){
                SectorIndexHistoricals::updateOrCreate(
                    [
                        'sector' => Stock::formatMarketIndex($indexName),
                        'date' => date("Y-m-d")
                    ], 
                    [
                        'sector' => Stock::formatMarketIndex($indexName),
                        'stock_index' => 1,
                        'date' => date("Y-m-d"),
                        'total_sector_market_cap' => $totalIndexMarketCap,
                        'day_change' => round(SectorIndexHistoricals::getSectorPercentChange($indexName, $stocksInIndex), 2),
                        'average_sector_market_cap' => $totalIndexMarketCap/count($stocksInIndex)
                    ]
                );

                if($this->option('mode') == 'full'){
                    foreach($this->indexMetrics as $metricName){
                        SectorIndexHistoricals::updateOrCreate(
                            [
                                'sector' => Stock::formatMarketIndex($indexName),
                                'date' => date("Y-m-d")
                            ], 
                            [
                                $metricName => round(StockMetrics::getAverageMetric($metricName, $stocksInIndex, $indexName), 2),
                            ]
                        );
                    }
                }
            }
        }
    }
}
