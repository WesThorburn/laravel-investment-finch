<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\SectorIndexHistoricals;
use App\Models\Stock;
use App\Models\StockMetrics;
use App\Models\Historicals;

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
            $listofDates = Historicals::where('stock_code', 'TLS')
                ->where('date', '>', '2016-04-18')
                ->where('date', '<', '2016-08-26')
                ->orderBy('date', 'DESC')
                ->lists('date');

            $listOfSectors = Stock::getListOfSectors();
            array_push($listOfSectors, 'All');

            $numberOfDates = count($listofDates);

            $previousDate = null;

            foreach($listofDates as $dateKey => $date){
                $this->info("Processing Date: ".$date);
                foreach($listOfSectors as $sectorName){
                    $this->info("Processing: ".$sectorName);
                    $stocksInSector = Stock::where('sector', $sectorName)->lists('stock_code');
                    if($sectorName ==  'All'){
                        $stocksInSector = Stock::lists('stock_code');
                    }

                    if(count($stocksInSector) > 0){
                        $totalSectorMarketCap = BackfillSectorCapData::getTotalSectorMarketCap($date, $stocksInSector);
                        SectorIndexHistoricals::updateOrCreate(
                            [
                                'sector' => $sectorName,
                                'date' => $date
                            ], 
                            [
                                'sector' => $sectorName,
                                'date' => $date,
                                'total_sector_market_cap' => $totalSectorMarketCap,
                                'average_sector_market_cap' => $totalSectorMarketCap/count($stocksInSector)
                            ]
                        );

                        if($previousDate){
                            $previousSectorHistorical = SectorIndexHistoricals::where(['date' => $previousDate, 'sector' => $sectorName])->first();
                            if($previousSectorHistorical->total_sector_market_cap > 0){
                                //Dates are DESC which means previous record is the day AFTER the current record (previous-current)
                                $dayChange = $previousSectorHistorical->total_sector_market_cap-$totalSectorMarketCap;
                                $previousSectorHistorical->day_change = round((100/$previousSectorHistorical->total_sector_market_cap)*($dayChange), 3);
                                $previousSectorHistorical->save();
                            }
                        }
                    }
                    $this->info("Completed: ".$sectorName." Total Sector Cap: ".$totalSectorMarketCap);
                }
                $previousDate = $date;
                $this->info("Completed: ".$date. " ".round((100/$numberOfDates)*($dateKey+1), 2)."%");
            }
        }
    }

    private function getTotalSectorMarketCap($date, $stocksInSector){
        $totalSectorMarketCap = 0;
        
        foreach($stocksInSector as $stockCode){
            $stockPriceAtDate = Historicals::where('stock_code', $stockCode)->where('date', $date)->pluck('close');
            $numberOfShares = StockMetrics::where('stock_code', $stockCode)->pluck('shares');
            $marketCapAtDate = $stockPriceAtDate * $numberOfShares / 1000000;
            $totalSectorMarketCap = $totalSectorMarketCap + $marketCapAtDate;
        }
        return $totalSectorMarketCap;
    }
}
