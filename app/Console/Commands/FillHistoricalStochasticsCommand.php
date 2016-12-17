<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Models\Historicals;
use Illuminate\Console\Command;

class FillHistoricalStochasticsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:fillHistoricalStochastics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fills the historical %K and %D values.';

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

            //Calculate Stochastic Ks
            foreach($historicalRecords as $record){
                $listOfHighs = Historicals::where('stock_code', $stockCode)
                    ->where('date', '<' , $record->date)
                    ->orderBy('date', 'DESC')
                    ->limit(5)
                    ->lists('high');

                $listOfLows = Historicals::where('stock_code', $stockCode)
                    ->where('date', '<', $record->date)
                    ->orderBy('date', 'DESC')
                    ->limit(5)
                    ->lists('low');

                if($listOfHighs->max() - $listOfLows->min() != 0){
                    $record->stochastic_k = ($record->close - $listOfLows->min())/($listOfHighs->max() - $listOfLows->min()) * 100;
                }
                else{
                    $record->stochastic_k = 0;
                }
                $record->save();
            }

            //Calculate Stochastic Ds
            foreach($historicalRecords as $record){
                $stochasticKRecords = Historicals::where('stock_code', $stockCode)
                    ->where('date', '<', $record->date)
                    ->orderBy('date', 'DESC')
                    ->limit(3)
                    ->lists('stochastic_k');

                if($stochasticKRecords->count() > 0){
                    $record->stochastic_d = $stochasticKRecords->sum()/$stochasticKRecords->count();
                    $record->save();
                }
            }
            $this->line("Completed ".round(($stockKey+1)*(100/$numberOfStocks), 2)."% | Stock: ".$stockCode);
        }
    }
}
