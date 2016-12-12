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
        if($this->confirm('This process may take several hours, do you wish to continue? [y|N]')){
            $uniqueStockCodes = Stock::all()->lists('stock_code');
            $numberOfStocks = count($uniqueStockCodes);

            foreach($uniqueStockCodes as $stockKey => $stockCode){
                $this->info("Processing Stock Code: ".$stockCode." ".round(($stockKey+1)*(100/$numberOfStocks), 2)."%");
                $historicalRecords = Historicals::where('stock_code', $stockCode)->where('date', '>', '2016-01-01')->orderBy('date', 'asc')->get();
                foreach($historicalRecords as $key => $record){
                    //populate signal line
                        //take 9-day ema of MACD line

                    //MACD histogram
                        //MACD line - signal line

                    $twelveDayMultiplier = (2/(12 + 1));
                    $twentySixDayMultiplier = (2/(26 + 1));

                    if($record->date == $historicalRecords->first()->date){ //First Date, we need the SMA
                        $twelveDayRecords = Historicals::where('stock_code', $stockCode)
                            ->orderBy('date', 'desc')
                            ->where('date', '<', '2016-01-01')
                            ->take(12)
                            ->lists('close');
                        if($twelveDayRecords->count() > 0){
                            $record->twelveDayEMA = $twelveDayRecords->sum()/$twelveDayRecords->count();
                        }

                        $twentySixDayRecords = Historicals::where('stock_code', $stockCode)
                            ->orderBy('date', 'desc')
                            ->where('date', '<', '2016-01-01')
                            ->take(26)
                            ->lists('close');
                        if($twentySixDayRecords->count() > 0){
                            $record->twentySixDayEMA = $twentySixDayRecords->sum()/$twelveDayRecords->count();
                        }
                    }
                    else{
                        $record->twelveDayEMA = ($record->close - $historicalRecords[$key-1]->twelveDayEMA) * $twelveDayMultiplier + $historicalRecords[$key-1]->twelveDayEMA;

                        $record->twentySixDayEMA = ($record->close - $historicalRecords[$key-1]->twentySixDayEMA) * $twentySixDayMultiplier + $historicalRecords[$key-1]->twentySixDayEMA;
                    }

                    Historicals::where(['stock_code' => $stockCode, 'date' => $record->date])->update(['macd_line' => $record->twelveDayEMA - $record->twentySixDayEMA]);
                }

                //Crunch signal line + update histogram
                $signalLineRecords = Historicals::where('stock_code', $stockCode)->where('date', '>', '2016-01-01')->whereNotNull('macd_line')->orderBy('date', 'asc')->get();

                $nineDayMultiplier = (2/(9 + 1));

                foreach($signalLineRecords as $key => $record){
                    if($record->date == $signalLineRecords->first()->date){
                        $record->nineDayMacdEMA = $record->macd_line;
                    }
                    else{
                        $record->nineDayMacdEMA = ($record->macd_line - $signalLineRecords[$key-1]->macd_line) * $nineDayMultiplier + $signalLineRecords[$key-1]->macd_line;
                    }
                    Historicals::where(['stock_code' => $stockCode, 'date' => $record->date])->update(['signal_line' => $record->nineDayMacdEMA, 'macd_histogram' => $record->macd_line - $record->nineDayMacdEMA]);
                }

                $this->line("Completed ".round(($stockKey+1)*(100/$numberOfStocks), 2)."% | Stock: ".$stockCode);
                
            }
        }
    }
}
