<?php

namespace App\Console\Commands;

use App\Models\Historicals;
use App\Models\Stock;
use App\Models\StockGains;
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
        $this->info("Calculating the stock changes... This may take several minutes.");
        $stockCodes = Stock::lists('stock_code');
        $numberOfStocks = Stock::count();
        foreach($stockCodes as $key => $stockCode){
            $lastTrade = StockMetrics::where('stock_code', $stockCode)->pluck('last_trade');
            
            $priceOneWeekAgo = CalculateStockChangeCommand::getPriceAtDate($stockCode, getDateFromCarbonDate(Carbon::now()->subWeek()));
            $weekChange = CalculateStockChangeCommand::getPercentChange($lastTrade, $priceOneWeekAgo);

            $priceTwoWeeksAgo = CalculateStockChangeCommand::getPriceAtDate($stockCode, getDateFromCarbonDate(Carbon::now()->subWeeks(2)));
            $twoWeekChange = CalculateStockChangeCommand::getPercentChange($lastTrade, $priceTwoWeeksAgo);
            
            $priceOneMonthAgo = CalculateStockChangeCommand::getPriceAtDate($stockCode, getDateFromCarbonDate(Carbon::now()->subMonth()));
            $oneMonthChange = CalculateStockChangeCommand::getPercentChange($lastTrade, $priceOneMonthAgo);         

            $priceTwoMonthsAgo = CalculateStockChangeCommand::getPriceAtDate($stockCode, getDateFromCarbonDate(Carbon::now()->subMonths(2)));
            $twoMonthChange = CalculateStockChangeCommand::getPercentChange($lastTrade, $priceTwoMonthsAgo);            

            $priceThreeMonthsAgo = CalculateStockChangeCommand::getPriceAtDate($stockCode, getDateFromCarbonDate(Carbon::now()->subMonths(3)));
            $threeMonthChange = CalculateStockChangeCommand::getPercentChange($lastTrade, $priceThreeMonthsAgo);
            
            $priceSixMonthsAgo = CalculateStockChangeCommand::getPriceAtDate($stockCode, getDateFromCarbonDate(Carbon::now()->subMonths(6)));
            $sixMonthChange = CalculateStockChangeCommand::getPercentChange($lastTrade, $priceSixMonthsAgo);

            $priceOneYearAgo = CalculateStockChangeCommand::getPriceAtDate($stockCode, getDateFromCarbonDate(Carbon::now()->subYear()));
            $oneYearChange = CalculateStockChangeCommand::getPercentChange($lastTrade, $priceOneYearAgo);     

            $priceTwoYearsAgo = CalculateStockChangeCommand::getPriceAtDate($stockCode, getDateFromCarbonDate(Carbon::now()->subYears(2)));
            $twoYearChange = CalculateStockChangeCommand::getPercentChange($lastTrade, $priceTwoYearsAgo);           

            $priceThreeYearsAgo = CalculateStockChangeCommand::getPriceAtDate($stockCode, getDateFromCarbonDate(Carbon::now()->subYears(3)));
            $threeYearChange = CalculateStockChangeCommand::getPercentChange($lastTrade, $priceThreeYearsAgo);
            
            $priceFiveYearsAgo = CalculateStockChangeCommand::getPriceAtDate($stockCode, getDateFromCarbonDate(Carbon::now()->subYears(5)));
            $fiveYearChange = CalculateStockChangeCommand::getPercentChange($lastTrade, $priceFiveYearsAgo);      

            $priceTenYearsAgo = CalculateStockChangeCommand::getPriceAtDate($stockCode, getDateFromCarbonDate(Carbon::now()->subYears(10)));
            $tenYearChange = CalculateStockChangeCommand::getPercentChange($lastTrade, $priceTenYearsAgo);
            
            if(Historicals::where('stock_code', $stockCode)->first()){
                $oldestDate = Historicals::where('stock_code', $stockCode)->orderBy('date', 'asc')->take(1)->lists('date');
                $oldestPriceAvailable = CalculateStockChangeCommand::getPriceAtDate($stockCode, substr($oldestDate, 2, -2));
                $allTimeChange = CalculateStockChangeCommand::getPercentChange($lastTrade, $oldestPriceAvailable); 
            }
            else{
                $allTimeChange = 0;
            }

            StockGains::updateOrCreate(['stock_code' => $stockCode], [
                'stock_code' => $stockCode,
                'week_change' => $weekChange,
                'two_week_change' => $twoWeekChange,
                'month_change' => $oneMonthChange,
                'two_month_change' => $twoMonthChange,
                'three_month_change' => $threeMonthChange,
                'six_month_change' => $sixMonthChange,
                'year_change' => $oneYearChange,
                'two_year_change' => $twoYearChange,
                'three_year_change' => $threeYearChange,
                'five_year_change' => $fiveYearChange,
                'ten_year_change' => $tenYearChange,
                'all_time_change' => $allTimeChange,
                'updated_at' => date("Y-m-d H:i:s")
            ]);
            $this->info("Completed: ".$stockCode." ".($key+1)."/".$numberOfStocks." - ".round(($key+1)*(100/$numberOfStocks), 2)."%");
        }
    }

    private static function getPriceAtDate($stockCode, $date){
        $price = Historicals::where('stock_code', $stockCode)->where('date', $date)->first();
        if(!$price){
            $startDate = getDateFromCarbonDate(getCarbonDateFromDate($date)->subDays(3));
            return Historicals::where('stock_code', $stockCode)->whereBetween('date', array($startDate, $date))->take(1)->pluck('close');
        }
        return $price->close;
    }

    private static function getPercentChange($currentPrice, $startingPrice){
        if($startingPrice > 0){
            return round(($currentPrice-$startingPrice)*(100/$startingPrice), 2);
        }
        return 0;
    }
}
