<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Stock;
use App\Models\StockMetrics;
use App\Models\Historicals;

class CalculateTrendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:calculateTrend {--testMode=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Calculates trends based on stocks' historical data.";

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
        $stockCodes = Stock::lists('stock_code');

        if($this->option('testMode') == 'true'){
            $this->info("[Test Mode]");
            $stockCodes = ['CBA', 'TLS'];
        }

        $numberOfStocks = count($stockCodes);
        foreach($stockCodes as $key => $stock){
        	$stockMetrics = StockMetrics::where('stock_code', $stock)->first();
            if($stockMetrics){
            	$stockMetrics->trend_short_term = $this->getTrend($stock, 50);
            	$stockMetrics->trend_medium_term = $this->getTrend($stock, 150);
            	$stockMetrics->trend_long_term = $this->getTrend($stock, 250);
            	$stockMetrics->save();
            }
			$this->info($stock ." | ". round($key * (100/$numberOfStocks), 2).'%');
        }
        $this->populateTrendTable();
    }

    public function getTrend($stockCode, $timeFrame){
    	$records = Historicals::select(['fifty_day_moving_average', 'two_hundred_day_moving_average'])
        	->where('stock_code', $stockCode)->orderBy('date', 'DESC')->take($timeFrame)->get();

        //Check to ensure $records isn't empty
        if($records->first()){
	    	$first50DayMA = $records->last()->fifty_day_moving_average;
	    	$first200DayMA = $records->last()->two_hundred_day_moving_average;
	    	$last50DayMA = $records->first()->fifty_day_moving_average;
	    	$last200DayMA = $records->first()->two_hundred_day_moving_average;

	    	$gradient = $this->getGradient(($last50DayMA - $first50DayMA), $timeFrame)*1000;
            return round($gradient, 2);
		}
		return "None";
    }

    private function populateTrendTable(){
        //Identify which stocks have a trend
        $trendingStocksHistoricals = Historicals::where('date', Historicals::getMostRecentHistoricalDate())
                                            ->whereRaw('fifty_day_moving_average < (two_hundred_day_moving_average * 1.03)')
                                            ->whereRaw('fifty_day_moving_average > (two_hundred_day_moving_average * 0.97)')
                                            ->lists('stock_code');

        $trendingStocksMetrics = StockMetrics::select('stock_code','trend_medium_term', 'trend_short_term')
                                    ->where('volume', '>', 1000)
                                    ->where('shares', '>', 0)
                                    ->where('deleted_at', null)
                                    ->whereIn('stock_code', $trendingStocksHistoricals)
                                    ->orderBy('trend_short_term', 'DESC')
                                    ->orderBy('trend_medium_term', 'DESC')
                                    ->get();

        //Store found stocks in trend table
        foreach($trendingStocksMetrics as $stock){
            DB::table('trends')->insert([
                'stock_code' => $stock->stock_code,
                'trend_type' => $stock->trend_short_term
            ]);
        }
    }

    private function getGradient($changeInY, $changeInX){
        return $changeInY/$changeInX;
    }
}
