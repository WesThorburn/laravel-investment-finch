<?php

namespace App\Console\Commands;

use App\Models\SectorHistoricals;
use App\Models\Stock;
use App\Models\StockMetrics;

use Illuminate\Console\Command;

class UpdateSectorChangeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:updateSectorChange';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the daily percentage change for each sector.';

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
        $this->info("Updating sector day changes...");
        $sectors = \DB::table('stocks')->select(\DB::raw('DISTINCT sector'))->lists('sector');
        foreach($sectors as $sector){
            $stocksInSector = Stock::where('sector', $sector)->lists('stock_code');
            $marketCaps = array();
            $marketCapDayChanges = array();
            foreach($stocksInSector as $stock){
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
                    'sector' => $sector,
                    'date' => date("Y-m-d")
                ], 
                [
                    'sector' => $sector,
                    'date' => date("Y-m-d"),
                    'day_change' => round($percentChange, 2)
                ]
            );
        }
        $this->info("Sector day changes have been updated!");
    }
}
