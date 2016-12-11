<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Historicals;
use App\Models\Stock;

class FillHistoricalMovingAveragesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:fillHistoricalMovingAverages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates the 20, 50 and 200 day moving averages for each stock in the historicals table.';

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
        if($this->confirm('This process may take several hours, do you wish to continue? [y|N]')){
            $uniqueStockCodes = Stock::all()->lists('stock_code');
            $numberOfStocks = count($uniqueStockCodes);

            foreach($uniqueStockCodes as $stockKey => $stockCode){
                $this->info("Processing Stock Code: ".$stockCode." ".round(($stockKey+1)*(100/$numberOfStocks), 2)."%");
                foreach([20] as $timeFrame){ 
                    $historicalRecords = Historicals::where('stock_code', $stockCode)->where('date', '>', '2016-01-01')->orderBy('date', 'desc')->get();
                    foreach($historicalRecords as $record){
                        $recordsInTimeFrame = Historicals::where('stock_code', $stockCode)
                            ->orderBy('date', 'desc')
                            ->where('date', '<', $record->date)
                            ->take($timeFrame)
                            ->lists('close');
                        if($recordsInTimeFrame->count() > 0){
                            $averageOfRecordsInTimeFrame = $recordsInTimeFrame->sum()/$recordsInTimeFrame->count();
                            if($timeFrame == 20){
                                Historicals::where(['stock_code' => $stockCode, 'date' => $record->date])->update(['twenty_day_moving_average' => $averageOfRecordsInTimeFrame]);
                            }
                        }
                    }
                    $this->line(round(($stockKey+1)*(100/$numberOfStocks), 2)."% | Stock: ".$stockCode." | ".$timeFrame." Day");
                }
            }
        }
    }

    private static function alreadyFilled($stockCode){
        $mostRecentRecord = Historicals::where('stock_code', $stockCode)->orderBy('date', 'desc')->first();
        if($mostRecentRecord->fifty_day_moving_average == 0.000 || $mostRecentRecord->two_hundred_day_moving_average == 0.000){
            return false;
        }
        return true;
    }
}
