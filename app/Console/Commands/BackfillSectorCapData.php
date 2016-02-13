<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SectorIndexHistoricals;
use App\Models\Stock;
use App\Models\StockMetrics;

class BackfillSectorCapData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:fillHistoricalSectorCapData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfills historical market cap data for each sector.';

    private $sectorMetrics = [
            'volume', 
            'EBITDA', 
            'earnings_per_share_current', 
            'earnings_per_share_next_year', 
            'price_to_earnings', 
            'price_to_book', 
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
        $this->info("This process may take several hours...");
        if($this->confirm('Do you wish to continue?')){
            $sectorHistoricalRecords = SectorIndexHistoricals::where('date', '>', '2015-08-12')
                ->where('date', '<', '2016-02-10')
                ->orderBy('date', 'DESC')
                ->take(10)
                ->lists('date');

            $listOfSectors = Stock::getListOfSectors();
            array_push($listOfSectors, 'All');

            $numberOfDates = count($sectorHistoricalRecords);

            foreach($sectorHistoricalRecords as $dateKey => $date){
                $this->info("Processing Date: ".$date);
                foreach($listOfSectors as $sectorName){
                    $this->info("Processing: ".$sectorName);
                    $stocksInSector = Stock::where('sector', $sectorName)->lists('stock_code');
                    if($sectorName ==  'All'){
                        $stocksInSector = Stock::lists('stock_code');
                    }
                    if(count($stocksInSector) > 0){
                        $totalSectorMarketCap = SectorIndexHistoricals::getTotalSectorMarketCap($stocksInSector);
                        SectorIndexHistoricals::updateOrCreate(
                            [
                                'sector' => $sectorName,
                                'date' => $date
                            ], 
                            [
                                'sector' => $sectorName,
                                'date' => $date,
                                'total_sector_market_cap' => $totalSectorMarketCap,
                                'day_change' => round(SectorIndexHistoricals::getSectorPercentChange($sectorName, $stocksInSector), 2),
                                'average_sector_market_cap' => $totalSectorMarketCap/count($stocksInSector)
                            ]
                        );

                        foreach($this->sectorMetrics as $metricName){
                            $this->info("Sector: ".$sectorName."  Processing Metric: ".$metricName);
                            SectorIndexHistoricals::updateOrCreate(
                                [
                                    'sector' => $sectorName,
                                    'date' => $date
                                ], 
                                [
                                    $metricName => round(StockMetrics::getAverageMetric($metricName, $stocksInSector, $sectorName), 2),
                                ]
                            );
                            $this->info("Sector: ".$sectorName."  Completed Metric: ".$metricName);
                        }
                    }
                    $this->info("Completed: ".$sectorName);
                }
                $this->info("Completed: ".$date. " ".round((100/$numberOfDates)*($dateKey+1), 2)."%");
            }
        }
    }
}
