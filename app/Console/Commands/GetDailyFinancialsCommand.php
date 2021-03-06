<?php

namespace App\Console\Commands;

use App\Models\Historicals;
use App\Models\Stock;
use App\Models\StockMetrics;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetDailyFinancialsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:getDailyFinancials {--testMode=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the daily trade data of each stock.';

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
        $this->info("Getting daily financials...");
        $stockCodes = Stock::all()->lists('stock_code');

        $numberOfStocks = count($stockCodes);

        $runningDate = date("Y-m-d"); //In case function runs past midnight

        if($this->option('testMode') == 'true'){
            $this->info("[Test Mode]");
            $stockCodes = ['CBA','TLS'];
        }
		foreach($stockCodes as $key => $stockCode){
            if(isTradingDay()){
                $stockMetrics = StockMetrics::where('stock_code', $stockCode)->first();

                $twelveDayEma = Historicals::getEMA($stockCode, 12);
                $twentySixDayEma = Historicals::getEMA($stockCode, 26);
                $macdLine = $twelveDayEma - $twentySixDayEma;
                $signalLine = Historicals::getSignalLine($stockCode, $macdLine);

                $typicalPrice = ($stockMetrics->high + $stockMetrics->low + $stockMetrics->close)/3;

				Historicals::updateOrCreate(['stock_code' => $stockCode, 'date' => $runningDate], [
					"stock_code" => $stockCode,
					"date" => $runningDate,
					"open" => $stockMetrics->open,
					"high" => $stockMetrics->high,
					"low" => $stockMetrics->low,
					"close" => $stockMetrics->close,
                    "percent_change" => $stockMetrics->percent_change,
                    "day_change" => $stockMetrics->day_change,
					"volume" => $stockMetrics->volume,
                    "shares" => $stockMetrics->shares,
					"adj_close" => $stockMetrics->adj_close,
                    "twenty_day_moving_average" => Historicals::getMovingAverage($stockCode, 20),
                    "fifty_day_moving_average" => Historicals::getMovingAverage($stockCode, 50),
                    "two_hundred_day_moving_average" => Historicals::getMovingAverage($stockCode, 200),
                    "twelve_day_ema" => $twelveDayEma,
                    "twenty_six_day_ema" => $twentySixDayEma,
                    "macd_line" =>  $macdLine,
                    "signal_line" => $signalLine,
                    "macd_histogram" => $macdLine - $signalLine,
                    "stochastic_k" => Historicals::getStochasticK($stockCode, 5),
                    "stochastic_d" => Historicals::getStochasticD($stockCode, 3),
                    "obv" => Historicals::getOBV($stockCode),
                    "five_day_rsi" => Historicals::getRSI($stockCode),
                    "typical_price" => $typicalPrice,
                    "cci" => Historicals::getCCI($stockCode, $typicalPrice),
                    "EBITDA" => $stockMetrics->EBITDA,
                    "earnings_per_share_current"=> $stockMetrics->earnings_per_share_current,
                    "earnings_per_share_next_year"=> $stockMetrics->earnings_per_share_next_year,
                    "price_to_earnings"=> $stockMetrics->price_to_earnings,
                    "price_to_sales"=> $stockMetrics->price_to_sales,
                    "price_to_book"=> $stockMetrics->price_to_book,
                    "peg_ratio" => $stockMetrics->peg_ratio,
                    "year_high"=> $stockMetrics->year_high,
                    "year_low"=> $stockMetrics->year_low,
                    "market_cap"=> $stockMetrics->current_market_cap,
                    "dividend_yield"=> $stockMetrics->dividend_yield,
                    "trend_short_term"=> $stockMetrics->trend_short_term,
                    "trend_medium_term"=> $stockMetrics->trend_medium_term,
                    "trend_long_term"=> $stockMetrics->trend_long_term,
					"updated_at" => date("Y-m-d H:i:s")
				]);
            }
			$this->info("Updating... ".round($key*(100/$numberOfStocks), 2)."%");
		}

        if(isTradingDay() && $this->option('testMode') != 'true'){
            $this->info("Removing existing stock_code index in historicals");
            \DB::statement("DROP INDEX `stock_code` ON historicals");
            $this->info("Reapplying index to historicals table");
            \DB::statement("ALTER TABLE `historicals` ADD INDEX (`stock_code`)");
            $this->info("Finished getting daily financials for ".$numberOfStocks. " stocks.");
        }
    }
}
