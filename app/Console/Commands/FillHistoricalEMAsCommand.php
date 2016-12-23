<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Models\Historicals;
use Illuminate\Console\Command;

class FillHistoricalEMAsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:fillHistoricalEMAs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fills the historical 12 and 26 day EMAs';

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

            //Fill first-day SMA
            /*$twelveDaySMA = null;
            $twentySixDaySMA = null;

            $firstHistorical = Historicals::where('stock_code', $stockCode)
                ->where('date', '>', '2016-01-01')
                ->orderBy('date', 'asc')
                ->first();

            $twelveDayRecords = Historicals::where('stock_code', $stockCode)
                ->orderBy('date', 'desc')
                ->where('date', '<', '2016-01-01')
                ->take(12)
                ->lists('close');

            if($twelveDayRecords->count() > 0){
                $twelveDaySMA = $twelveDayRecords->sum()/$twelveDayRecords->count();
            }

            $twentySixDayRecords = Historicals::where('stock_code', $stockCode)
                ->orderBy('date', 'desc')
                ->where('date', '<', '2016-01-01')
                ->take(26)
                ->lists('close');

            if($twentySixDayRecords->count() > 0){
                $twentySixDaySMA = $twentySixDayRecords->sum()/$twentySixDayRecords->count();
            }

            $firstHistorical->twelve_day_ema = $twelveDaySMA;
            $firstHistorical->twenty_six_day_ema = $twentySixDaySMA;
            $firstHistorical->save();*/

            //Fill non-first day EMAs
            $historicals = Historicals::where('stock_code', $stockCode)
                ->where('date', '>', '2016-12-15')
                ->orderBy('date', 'asc')
                ->get();

            foreach($historicals as $key => $record){
                if($key > 0){ //Skip first one, (that's the SMA)
                    $twelveDayMultiplier = 2/(12+1);
                    $twentySixDayMultiplier = 2/(26+1);
                    if($record->date == '2016-12-21' || $record->date == '2016-12-20' || $record->date == '2016-12-19'){
                        $previousDayTwelveDayEMA = $historicals[$key-1]->twelve_day_ema;
                        $previousDayTwentySixDayEMA = $historicals[$key-1]->twenty_six_day_ema;

                        $record->twelve_day_ema = ($record->close - $previousDayTwelveDayEMA) * $twelveDayMultiplier + $previousDayTwelveDayEMA;
                        $record->twenty_six_day_ema = ($record->close - $previousDayTwentySixDayEMA) * $twentySixDayMultiplier + $previousDayTwentySixDayEMA;

                        $record->save();
                    }
                }
            }
        }
    }
}
