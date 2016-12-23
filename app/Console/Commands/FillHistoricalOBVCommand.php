<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Historicals;
use App\Models\Stock;

class FillHistoricalOBVCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:fillHistoricalOBV';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fills the historical on balance volumes.';

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
                if($record->date == $historicalRecords->first()->date){
                    $record->obv = $record->volume;
                }
                else{
                    if($record->percent_change > 0){
                        $record->obv = $historicalRecords[$key-1]->obv + $record->volume;
                    }
                    else if($record->percent_change < 0){
                        $record->obv = $historicalRecords[$key-1]->obv - $record->volume;
                    }
                    else if($record->percent_change == 0){
                        $record->obv = $historicalRecords[$key-1]->obv;
                    }
                }
                $record->save();
            }
            $this->line("Completed ".round(($stockKey+1)*(100/$numberOfStocks), 2)."% | Stock: ".$stockCode);
        }
    }
}
