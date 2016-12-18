<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Historicals;
use App\Models\Stock;

class FillHistoricalRSICommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:fillHistoricalRSI';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fills the historical 5-day RSI fields.';

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
        $uniqueStockCodes = Stock::all()->lists('stock_code');
        $numberOfStocks = count($uniqueStockCodes);

        foreach($uniqueStockCodes as $stockKey => $stockCode){
            $this->info("Processing Stock Code: ".$stockCode." ".round(($stockKey+1)*(100/$numberOfStocks), 2)."%");

            $historicalRecords = Historicals::where('stock_code', $stockCode)
                ->where('date', '>', '2016-01-01')
                ->orderBy('date', 'asc')
                ->get();

            foreach($historicalRecords as $key => $record){
                $fiveDayChanges = Historicals::where('stock_code', $stockCode)
                    ->where('date', '<', $record->date)
                    ->orderBy('date', 'DESC')
                    ->limit(5)
                    ->lists('day_change');

                $fiveDayGains = [];
                $fiveDayLosses = [];

                foreach($fiveDayChanges as $dayChange){
                    if($dayChange > 0){
                        array_push($fiveDayGains, abs($dayChange));
                    }
                    else if($dayChange < 0){
                        array_push($fiveDayLosses, abs($dayChange));
                    }
                }

                if(count($fiveDayGains) > 0){
                    $averageGain = array_sum($fiveDayGains)/count($fiveDayGains);
                }
                else{
                    $averageGain = 0;
                }
                
                if(count($fiveDayLosses) > 0){
                    $averageLoss = array_sum($fiveDayLosses)/count($fiveDayLosses);
                }
                else{
                    $averageLoss = 0;
                }

                if($averageLoss != 0){
                    $record->five_day_rsi = 100 - 100 / (1 + ($averageGain / $averageLoss));
                }
                else{
                    $record->five_day_rsi = 100;
                }
                
                $record->save();
            }

            $this->line("Completed ".round(($stockKey+1)*(100/$numberOfStocks), 2)."% | Stock: ".$stockCode);
        }
    }
}
