<?php namespace App\Console\Commands;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Models\StockMetrics;
use App\Models\Stock;
use App\Models\Historicals;
use Carbon\Carbon;

class UpdateStockMetricsCommand extends Command {
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'stocks:updateStockMetrics';
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Updates stock metrics from Yahoo finance data.";
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
	public function fire()
	{
		$this->info('Updating stock metrics... This may take several minutes...');
		$iterationNumber = 1;
		$maxIterations = ceil(Stock::count()/100);
		if($this->option('testMode')){
			$maxIterations = 1;
			$this->info("[Test Mode]");
		}

		UpdateStockMetricsCommand::insertMetricRowsForNewStocks();

		while($iterationNumber <= $maxIterations){
			$stockCodeParameter = UpdateStockMetricsCommand::getStockCodeParameter($this->option('testMode'));
			$stockURL = "http://finance.yahoo.com/d/quotes.csv?s=".$stockCodeParameter."&f=sl1p2c1ohgvf6j4ee8p5rp6r5kjj1y";
			$metrics = explode("\n", @file_get_contents($stockURL));
			foreach($metrics as $metric){
				if($metric != null){

					$individualMetric = explode(',', $metric);
					$stockCode = substr(explode('.', $individualMetric[0])[0], 1);
					$numberOfShares = UpdateStockMetricsCommand::correctNumberOfShares($stockCode, $individualMetric[8]);

					if($individualMetric[1]){
						StockMetrics::updateOrCreate(['stock_code' => $stockCode], [
							"stock_code" => $stockCode,
							"last_trade" => $individualMetric[1],
							"percent_change" => UpdateStockMetricsCommand::correctPercentChange($individualMetric[1], substr($individualMetric[2], 1, -2), $stockCode),
							'day_change' => $individualMetric[3],
							"open" => $individualMetric[4],
							"high" => $individualMetric[5],
							"low" => $individualMetric[6],
							"close" => $individualMetric[1], //Last Trade after closing time
							"adj_close" => 0.000, //No Data Available
							"volume" => UpdateStockMetricsCommand::correctVolume($individualMetric[7], $stockCode),
							"shares" => $numberOfShares,
							"EBITDA" => UpdateStockMetricsCommand::formatEBITDA($individualMetric[9]),
							"earnings_per_share_current" => $individualMetric[10],
							"earnings_per_share_next_year" => $individualMetric[11],
							"price_to_sales" => $individualMetric[12],
							"price_to_earnings" => $individualMetric[13],
							"price_to_book" => $individualMetric[14],
							"peg_ratio" => $individualMetric[15],
							"year_high" => $individualMetric[16],
							"year_low" => $individualMetric[17],
							"current_market_cap" => $individualMetric[8] * $individualMetric[1]/1000000, //Market cap is number of shares * last trade / 1,000,000
							"dividend_yield" => $individualMetric[19],
							"updated_at" => date("Y-m-d H:i:s")
						]);
					}
				}
			}
			$this->info("Updating... ".round(($iterationNumber)*(100/$maxIterations), 2)."%");
			$iterationNumber++;
		}
		$this->info('All stock metrics were updated successfully!');
	}
	//Creates rows for stocks that aren't yet in the metrics table (Required because getStockCodeParameter gets the 100 oldest metrics)
	private static function insertMetricRowsForNewStocks(){
		$stockCodes = Stock::lists('stock_code');
		foreach($stockCodes as $stockCode){
			StockMetrics::updateOrCreate(['stock_code' => $stockCode], []);
		}
	}
	//Gets list of stock codes separated by addition symbols, only TLS and CBA in test mode
	private static function getStockCodeParameter($testMode = false){
		if(!$testMode){
			//Limit of 100 at a time due to yahoo's url length limit
			$stockCodeList = StockMetrics::where('updated_at', '<', Carbon::now()->subSeconds(60))->orderBy('updated_at')->take(100)->lists('stock_code');
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
	//Checks EBITDA value and calls appropriate helper function to format it
	private static function formatEBITDA($ebitda){
		if(substr($ebitda, -1) == 'B' || substr($ebitda, -1) == 'M'){
			return formatMoneyAmountToNumber($ebitda);
		}
		elseif($ebitda > 10000 || $ebitda < -10000){
			return formatHundredThousandToMillion($ebitda);
		}
		return $ebitda;
	}

	//Checks number of shares provided by API, if zero, use most recent non-zero number
	private static function correctNumberOfShares($stockCode, $numberOfShares){
		if($numberOfShares == 'N/A'){
			return Historicals::where('stock_code', $stockCode)->where('shares', '!=', 0)->orderBy('date', 'DESC')->pluck('shares');
		}
		return $numberOfShares;
	}

	//Nulls current day's percentage change if it's the exact same as yesterday's
	private static function correctPercentChange($lastTrade, $percentChange, $stockCode){
		$mostRecentHistoricalDate = Historicals::getMostRecentHistoricalDate();
		$yesterdaysClose = Historicals::where(['stock_code' => $stockCode, 'date' => $mostRecentHistoricalDate])->pluck('close');
		if($yesterdaysClose && $percentChange != 0 && $lastTrade == $yesterdaysClose){
			return 0;
		}
		return $percentChange;
	}

	//Nulls current day's volume if it's the exact same as yesterday's
	private static function correctVolume($volume, $stockCode){
		$mostRecentHistoricalDate = Historicals::getMostRecentHistoricalDate();
		$yesterdaysVolume = Historicals::where(['stock_code' => $stockCode, 'date' => $mostRecentHistoricalDate])->pluck('volume');
		if($yesterdaysVolume == $volume){
			return 0;
		}
		return $volume;
	}
	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['example', InputArgument::OPTIONAL, 'An example argument.'],
		];
	}
	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['testMode', null, InputOption::VALUE_OPTIONAL, 'Runs the command in Test Mode.', false],
		];
	}
}