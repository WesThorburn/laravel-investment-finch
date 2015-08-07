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
		$companyListFromASX = array_slice(explode(PHP_EOL, file_get_contents("http://www.asx.com.au/asx/research/ASXListedCompanies.csv")), 3);
		$numberOfStocks = count($companyListFromASX);
		foreach($companyListFromASX as $key => $companyRow){
			if($companyRow != null){
				$stockCode = explode(',"', explode('",', $companyRow)[1])[0];
				//Only insert full stock list if not in test mode
				if(!$this->option('testMode')){
					Stock::updateOrCreate(['stock_code' => $stockCode], [
						'stock_code' => explode(',"', explode('",', $companyRow)[1])[0], 
					    'company_name' => substr(explode('",', $companyRow)[0], 1),
					    'sector' => substr(explode(',"', explode('",', $companyRow)[1])[1], 0, -2),
					    'updated_at' => date("Y-m-d H:i:s")
					]);
					$this->info("Updating... ".round(($key+1)*(100/$numberOfStocks), 2)."%");
				}
				else{
					if($stockCode == 'TLS' || $stockCode == 'CBA'){
						$stock = Stock::where('stock_code', $stockCode)->first();
						$stock->updated_at = date("Y-m-d H:i:s");
						$stock->save();
						$this->info("[Test Mode] Updated...".$stockCode);
					}
				}
			}
		}
		$this->info('The list of stocks was updated successfully!');
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
