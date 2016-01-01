<?php

namespace App\Console\Commands;

use App\Models\Historicals;
use App\Models\Stock;
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
        if(isTradingDay()){
            $this->info("This process can take several minutes...");
            $this->info("Getting daily financials...");
            $stockCodes = Stock::all()->lists('stock_code');

            $numberOfStocks = count($stockCodes);
			$iterationNumber = 1;
			$maxIterations = ceil($numberOfStocks/100);
            if($this->option('testMode') == 'true'){
                $maxIterations = 1;
                $this->info("[Test Mode]");
            }
			while($iterationNumber <= $maxIterations){
                $stockCodeParameter = GetDailyFinancialsCommand::getStockCodeParameter($this->option('testMode'));
                $financialsURL = "http://finance.yahoo.com/d/quotes.csv?s=".$stockCodeParameter."&f=sohgl1v";
				$dailyRecords = explode("\n", file_get_contents($financialsURL));
				foreach($dailyRecords as $record){
					if($record != null){
						$individualRecord = explode(',', $record);
						$stockCode = substr(explode('.', $individualRecord[0])[0], 1);
						Historicals::updateOrCreate(['stock_code' => $stockCode, 'date' => date("Y-m-d")], [
							"stock_code" => $stockCode,
							"date" => date("Y-m-d"),
							"open" => $individualRecord[1],
							"high" => $individualRecord[2],
							"low" => $individualRecord[3],
							"close" => $individualRecord[4],
							"volume" => $individualRecord[5],
							"adj_close" => $individualRecord[4],
                            "fifty_day_moving_average" => Historicals::getMovingAverage($stockCode, 50),
                            "two_hundred_day_moving_average" => Historicals::getMovingAverage($stockCode, 200),
							"updated_at" => date("Y-m-d H:i:s")
						]);
					}
				}
				$this->info("Updating... ".round(($iterationNumber)*(100/$maxIterations), 2)."%");
				$iterationNumber++;
			}

            if($this->option('testMode') != 'true'){
                $this->info("Removing existing stock_code index in historicals");
                \DB::statement("DROP INDEX `stock_code` ON historicals");
                $this->info("Reapplying index to historicals table");
                \DB::statement("ALTER TABLE `historicals` ADD INDEX (`stock_code`)");
                $this->info("Finished getting daily financials for ".$numberOfStocks. " stocks.");
            }
        }
        else{
            $this->info("This command can only be run on trading days.");
        }
    }

    //Gets list of stock codes separated by addition symbols
	private static function getStockCodeParameter($testMode = 'false'){
        if($testMode != 'true'){
            date_default_timezone_set("Australia/Sydney");
            //Limit of 100 at a time due to yahoo's url length limit
            $stockCodeList = Stock::whereNotIn('stock_code', Historicals::distinct()->where('date',date("Y-m-d"))->lists('stock_code'))->take(100)->lists('stock_code');
            $stockCodeParameter = "";
            foreach($stockCodeList as $stockCode){
                $stockCodeParameter .= "+".$stockCode.".AX";
            }
            return substr($stockCodeParameter, 1);
        }
        else{
            return "TLS.AX+CBA.AX";
        }
	}
}
