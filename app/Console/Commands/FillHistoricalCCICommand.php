<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Historicals;
use App\Models\Stock;

class FillHistoricalCCICommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:fillHistoricalCCI';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fills the historical Typical Price and CCI fields. ';

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

            if($historicalRecords->last()->cci == null){
                //Calculate typical price
                foreach($historicalRecords as $key => $record){
                    $record->typical_price = ($record->high + $record->low + $record->close)/3;
                    $record->save();
                }

                //Calculate CCI
                foreach($historicalRecords as $key => $record){
                    $typicalPriceRecords = Historicals::where('stock_code', $stockCode)
                        ->where('date', '<', $record->date)
                        ->orderBy('date', 'DESC')
                        ->limit(20)
                        ->lists('typical_price');

                    //Calculate 20-Period SMA
                    if($typicalPriceRecords->count() != 0){
                        $typicalPriceSMA = $typicalPriceRecords->sum()/$typicalPriceRecords->count();
                    }
                    else{
                        $typicalPriceSMA = $record->typical_price;
                    }

                    //Calculate Mean Deviation
                    foreach($typicalPriceRecords as $typicalPrice){
                        $typicalPrice = abs($typicalPrice - $typicalPriceSMA);
                    }
                    if($typicalPriceRecords->count() != 0){
                        $meanDeviation = $typicalPriceRecords->sum()/$typicalPriceRecords->count();
                    }
                    else{
                        $meanDeviation = 0;
                    }

                    if($meanDeviation != 0){
                        $record->cci = ($record->typical_price - $typicalPriceSMA)/(0.15 * $meanDeviation);
                        $record->save();
                    }
                }
            }
            $this->line("Completed ".round(($stockKey+1)*(100/$numberOfStocks), 2)."% | Stock: ".$stockCode);
        }
    }
}
