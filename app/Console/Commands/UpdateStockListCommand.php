<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

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
		$masterStockList = array();
		foreach($companyListFromASX as $companyRow){
			if($companyRow != null){
				$companyRowEnd = explode(',"', explode('",', $companyRow)[1])[1];
				if(substr($companyRowEnd, -1) == '"'){
					$sector = substr($companyRowEnd, 0, -1);
				}
				else{
					$sector = $companyRowEnd;
				}
				array_push($masterStockList, array(
					"stock_code" => explode(',"', explode('",', $companyRow)[1])[0], 
				    'company_name' => substr(explode('",', $companyRow)[0], 1),
				    'sector' => $sector
				));
			}
		}
		\DB::table('stocks')->truncate();
		\DB::table('stocks')->insert($masterStockList);
		$this->info('The list of stocks was updated successfully!');
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
