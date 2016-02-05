<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Historicals;
use App\Models\Stock;
use App\Models\StockMetrics;
use App\Models\SectorIndexHistoricals;

class FillSectorIndexHistoricalsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:fillSectorIndexHistoricals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Calculates the historical sector change for each sectors based on the provided date ('yyyy-mm-dd').";

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
        $providedDate = $this->ask("Which date would you like to get the sector historicals for? ('yyyy-mm-dd')");
        $historicalsAtDate = Historicals::where('date', $providedDate)->get();
        $sectors = \DB::table('stocks')->select(\DB::raw('DISTINCT sector'))->lists('sector');
        foreach($sectors as $sector){
            $stocksInSector = Stock::where('sector', $sector)->lists('stock_code');
            FillSectorIndexHistoricalsCommand::calculateDayGain($stocksInSector, $sector, $providedDate);
        }
        $allStockCodes = Stock::lists('stock_code');
        FillSectorIndexHistoricalsCommand::calculateDayGain($allStockCodes, "All", $providedDate);
        $this->info("Sector historicals have been filled for: ".$providedDate);
    }

    private static function calculateDayGain($listOfStocks, $sectorName, $providedDate){
        $marketCaps = array();
        $marketCapDayChanges = array();
        foreach($listOfStocks as $stock){
            $marketCap = StockMetrics::where('stock_code', $stock)->pluck('market_cap');
            $open = Historicals::where('stock_code', $stock)->where('date', $providedDate)->pluck('open');
            $close = Historicals::where('stock_code', $stock)->where('date', $providedDate)->pluck('close');
            $dayChange = FillSectorIndexHistoricalsCommand::getDayChange($open, $close);
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
        
        SectorIndexHistoricals::updateOrCreate(
            [
                'sector' => $sectorName,
                'date' => $providedDate
            ], 
            [
                'sector' => $sectorName,
                'date' => $providedDate,
                'day_change' => round($percentChange, 2)
            ]
        );
    }

    private static function getDayChange($open, $close){
        if($open > 0){
            return (100 / $open) * ($close - $open);
        }
        return 0;
    }
}
