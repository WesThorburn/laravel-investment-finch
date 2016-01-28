<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Stock;

class GetASXListsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:getASXLists';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Identifies which stocks are in the ASX 20, 50, 100, 200, 300 and All Ords, then records them in the Stocks table.';

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
    	$indexes = ["asx20", "asx50", "asx100", "asx200", "asx300", "all-ordinaries"];

    	foreach($indexes as $index){
			$tableRowsArray = $this->getMarketIndexTableRows("http://www.marketindex.com.au/" . $index);
		    foreach($tableRowsArray as $row){
		    	$explodedRow = explode("<td>", $row);
		 		//Skip first row because it's blank
		    	if(count($explodedRow) > 1){
		    		$stockCode = substr($explodedRow[1],-21, 3);
		    		$this->setStockIndex($stockCode, $index);
		    	}
		    }
		    $this->info("Completed: ".$index);
		}
    }

    private function getMarketIndexTableRows($url){
    	//Only works with marketindex.com.au from ASX20 through ASX300
    	$wholePage = file_get_contents($url);
	    $pageAfterTableStart = explode('<div class="asx_sp_col1">', $wholePage);
	    $pageAfterTBodyStart = explode("<tbody>", $pageAfterTableStart[1])[1];
	    $tableContents = explode("</tbody>", $pageAfterTBodyStart)[0];
	    return explode('<tr>', $tableContents);
    }

    private function setStockIndex($stockCode, $index){
    	$stock = Stock::where('stock_code', $stockCode)->first();
		switch($index){
			case 'asx20':
				$stock->asx_20 = 1;
				break;
			case 'asx50':
				$stock->asx_50 = 1;
				break;
			case 'asx100':
				$stock->asx_100 = 1;
				break;
			case 'asx200':
				$stock->asx_200 = 1;
				break;
			case 'asx300':
				$stock->asx_300 = 1;
				break;
			case 'all-ordinaries':
				$stock->all_ords = 1;
				break;
    	}
    	$stock->save();
    }
}
