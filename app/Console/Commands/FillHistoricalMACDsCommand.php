<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Historicals;
use App\Models\Stock;

class FillHistoricalMACDsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:fillHistoricalMACDs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates the 12, 26 and 9 period MACD values for each stock.';

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
                ->where('date', '>', '2016-12-15')
                ->orderBy('date', 'asc')
                ->get();

            //Fill MACD Line
            foreach($historicalRecords as $record){
                if($record->date == '2016-12-21' || $record->date == '2016-12-20' || $record->date == '2016-12-19'){
                    $record->macd_line = $record->twelve_day_ema - $record->twenty_six_day_ema;
                    $record->save();
                }
            }

            //Crunch signal line + update histogram
            $signalLineRecords = Historicals::where('stock_code', $stockCode)
                ->where('date', '>', '2016-12-15')
                ->orderBy('date', 'asc')
                ->skip(10)
                ->limit(PHP_INT_MAX)
                ->get();

            if($signalLineRecords->count() < 1){
                $this->info("Insufficient data, skipping " . $stockCode);
                continue;
            }

            //Get 9 Day SMA for first signal line point
            $macdLineRecords = Historicals::where('stock_code', $stockCode)
                ->where('date', '<', $signalLineRecords->first()->date)
                ->orderBy('date', 'DESC')
                ->take(9)
                ->lists('macd_line');

            if($record->date == '2016-12-21' || $record->date == '2016-12-20' || $record->date == '2016-12-19'){
                if($macdLineRecords->count() > 0){
                    $signalLineRecords->first()->signal_line = $macdLineRecords->sum()/$macdLineRecords->count();
                }
                else{
                    $signalLineRecords->first()->signal_line = $signalLineRecords->first()->close;
                }
                $signalLineRecords->first()->save();
            }

            $nineDayMultiplier = (2/(9 + 1));

            foreach($signalLineRecords as $key => $record){
                if($key > 0){
                    if($record->date == '2016-12-21' || $record->date == '2016-12-20' || $record->date == '2016-12-19'){
                        $record->signal_line = ($record->macd_line - $signalLineRecords[$key-1]->macd_line) * $nineDayMultiplier + $signalLineRecords[$key-1]->macd_line;
                        $record->save();
                    }
                }
            }

            //Calculate Histogram
            $histogramRecords = Historicals::where('stock_code', $stockCode)
                ->where('date', '>', '2016-01-01')
                ->orderBy('date', 'asc')
                ->skip(10)
                ->limit(PHP_INT_MAX)
                ->get();

            foreach($histogramRecords as $record){
                if($record->date == '2016-12-21' || $record->date == '2016-12-20' || $record->date == '2016-12-19'){
                    $record->macd_histogram = $record->macd_line - $record->signal_line;
                    $record->save();
                }
            }

            $this->line("Completed ".round(($stockKey+1)*(100/$numberOfStocks), 2)."% | Stock: ".$stockCode);
        }
    }
}
