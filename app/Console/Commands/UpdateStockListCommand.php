<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Models\Stock;

class UpdateStockListCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'stocks:updateStockList';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Updates the list of stocks from the ASX website.';

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
		$companyListFromASX = UpdateStockListCommand::getCompanyList();

		if($this->option('testMode')){
			//Test Mode file only contains TLS and CBA
			$companyListFromASX = array_slice(explode(PHP_EOL, file_get_contents('database/files/testCompanyList.csv')), 3);
		}

		$numberOfStocks = count($companyListFromASX);
		$existingStocksList = Stock::lists('stock_code');
		$activeStocksList = [];

		foreach($companyListFromASX as $key => $companyRow){
			if($companyRow != null){
				$stockCode = explode(',"', explode('",', $companyRow)[1])[0];
				Stock::updateOrCreate(['stock_code' => $stockCode], [
					'stock_code' => explode(',"', explode('",', $companyRow)[1])[0], 
				    'company_name' => formatCompanyName($stockCode, substr(explode('",', $companyRow)[0], 1)),
				    'sector' => substr(explode(',"', explode('",', $companyRow)[1])[1], 0, -2),
				    'updated_at' => date("Y-m-d H:i:s")
				]);
				array_push($activeStocksList, $stockCode);
				$this->info("Updating... ".round(($key+1)*(100/$numberOfStocks), 2)."%");
			}
		}
		$inactiveStocks = array_diff($existingStocksList->toArray(), $activeStocksList);
		foreach($inactiveStocks as $stockCode){
			$stock = Stock::where('stock_code', $stockCode)->delete();
		}
		$this->info('The list of stocks was updated successfully!');
	}
	private static function getCompanyList(){
		return array_slice(explode(PHP_EOL, file_get_contents("http://www.asx.com.au/asx/research/ASXListedCompanies.csv")), 3);
	}
	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
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
