<?php

namespace App\Console\Commands;

use App\Models\Historicals;
use App\Models\Stock;
use App\Models\StockMetrics;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateStockChangeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:calculateStockChange';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates the overall stock changes.';

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
        $this->info("Calculating the stock changes.");
        $stockCodes = Stock::lists('stock_code');
        foreach($stockCodes as $stockCode){
            $lastTrade = StockMetrics::where('stock_code', $stockCode)->pluck('last_trade');
            $oldestDate = Historicals::where('stock_code', $stockCode)->orderBy('date', 'asc')->take(1)->lists('date');
            if($oldestDate){
                $explodedDate = explode('-', substr($oldestDate, 2, -2));
                $this->info($explodedDate[0]." ".$explodedDate[1]." ".$explodedDate[2]);
                Carbon::createFromDate($explodedDate[0], $explodedDate[1], $explodedDate[2]);

                
                $earliestPriceAvailable = Historicals::where('stock_code', $stockCode)->where('date', substr($oldestDate, 2, -2))->pluck('close');
            }
            $this->info("Code: ".$stockCode. "Price: ".$earliestPriceAvailable);
            $priceOneWeekAgo = '';
            $priceTwoWeeksAgo = '';
            $priceOneMonthAgo = '';
            $priceTwoMonthsAgo = '';
            $priceThreeMonthsAgo = '';
            $priceSixMonthsAgo = '';
            $priceOneYearAgo = '';
            $priceThreeYearsAgo = '';
            $priceFiveYearsAgo = '';
            $priceTenYearsAgo = '';
        }
    }
}
