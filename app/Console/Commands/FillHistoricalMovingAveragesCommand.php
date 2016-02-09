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
    protected $description = 'Calculates the 50 and 200 day moving averages for each stock in the historicals table.';

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
                foreach([50,200] as $timeFrame){
                    $historicalDates = Historicals::where(['stock_code' => $stockCode, 'date' => '2016-02-08'])->lists('date');
                    $numberOfDates = count($historicalDates);
                    foreach($historicalDates as $dateKey => $date){         
                        $recordsInTimeFrame = Historicals::where('stock_code', $stockCode)->orderBy('date', 'desc')->skip($dateKey)->take($timeFrame)->lists('close');
                        $averageOfRecordsInTimeFrame = $recordsInTimeFrame->sum()/$recordsInTimeFrame->count();
                        if($timeFrame == 50){
                            Historicals::where(['stock_code' => $stockCode, 'date' => $date])->update(['fifty_day_moving_average' => $averageOfRecordsInTimeFrame]);
                        }
                        elseif($timeFrame == 200){
                            Historicals::where(['stock_code' => $stockCode, 'date' => $date])->update(['two_hundred_day_moving_average' => $averageOfRecordsInTimeFrame]);
                        }
                        $this->line(round(($stockKey+1)*(100/$numberOfStocks), 2)."% | Stock: ".$stockCode." | ".$timeFrame." Day | Date: ".$date." ".round(($dateKey+1)*(100/$numberOfDates), 2)."%");
                    }
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
