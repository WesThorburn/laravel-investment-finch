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
    protected $signature = 'stocks:calculateTrend';

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

			if($first50DayMA < $first200DayMA && $last50DayMA > $last200DayMA){
				return "Up";
			}
			elseif($first50DayMA > $first200DayMA && $last50DayMA < $last200DayMA){
				return "Down";
			}
			return "None";
		}
		return "None";
    }
}
