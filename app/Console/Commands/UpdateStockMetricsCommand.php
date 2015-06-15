<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Models\StockMetrics;
use App\Models\Stock;

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
		\DB::table('stock_metrics')->truncate();
		while($iterationNumber <= ceil(Stock::count()/100)){
			$masterMetricList = array();
			$metrics = explode("\n", file_get_contents("http://finance.yahoo.com/d/quotes.csv?s=".UpdateStockMetricsCommand::getStockCodeParameter()."&f=sl1p2a2j4ee8rp6kjm3m4j1y"));
			foreach($metrics as $metric){
				if($metric != null){
					$individualMetric = explode(',', $metric);
					array_push($masterMetricList, array(
						"stock_code" => substr(explode('.', $individualMetric[0])[0], 1),
						"last_trade" => $individualMetric[1],
						"day_change" => substr($individualMetric[2], 1, -2),
						"average_daily_volume" => $individualMetric[3],
						"EBITDA" => $individualMetric[4],
						"earnings_per_share_current" => $individualMetric[5],
						"earnings_per_share_next_year" => $individualMetric[6],
						"price_to_earnings" => $individualMetric[7],
						"price_to_book" => $individualMetric[8],
						"year_high" => $individualMetric[9],
						"year_low" => $individualMetric[10],
						"fifty_day_moving_average" => $individualMetric[11],
						"two_hundred_day_moving_average" => $individualMetric[12],
						"market_cap" => $individualMetric[13],
						"dividend_yield" => $individualMetric[14]
					));
				}
			}
			\DB::table('stock_metrics')->insert($masterMetricList);
			$iterationNumber++;
		}

		$this->info('All stock metrics were updated successfully!');
	}

	//Gets list of stock codes separated by addition symbols
	private static function getStockCodeParameter(){
		//Limit of 100 at a time due to yahoo's url length limit
		$stockCodeList = Stock::whereNotIn('stock_code', StockMetrics::lists('stock_code'))->take(100)->lists('stock_code');
		$stockCodeParameter = "";
		foreach($stockCodeList as $stockCode){
			$stockCodeParameter .= "+".$stockCode.".AX";
		}
		return substr($stockCodeParameter, 1);
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
			['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}