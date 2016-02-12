<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Historicals;
use App\Models\Stock;

class BackfillMarketCapData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:fillHistoricalMarketCapData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfills historical ';

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
    	$this->info("This process may take several hours...");
        if($this->confirm('Do you wish to continue?')){
	        $stocks = Stock::lists('stock_code');
	        $numberOfStocks = count($stocks);
	        foreach($stocks as $stockKey => $stockCode){
	        	$this->info('Processing: '.$stockCode);

	        	//Backfill range is between 2015-08-12 and 2016-02-10
	        	$historicalRecordDates = Historicals::where('stock_code', $stockCode)
	        		->where('date', '>', '2015-08-12')
	        		->where('date', '<', '2016-02-10')
	        		->orderBy('date', 'DESC')
	        		->lists('date');

	        	$previousDate = null;
	        	foreach($historicalRecordDates as $date){
	        		//Skip first record
	        		if($previousDate){
	        			$currentHistoricalRecord = Historicals::where(['stock_code' => $stockCode, 'date' => $date])->first();
	        			$previousHistoricalRecord = Historicals::where(['stock_code' => $stockCode, 'date' => $previousDate])->first();

	        			$dayChange = $previousHistoricalRecord->close-$currentHistoricalRecord->close;
	        			$percentageChange = number_format((100/$previousHistoricalRecord->close)*($dayChange), 2);
	        			
	        			$previousHistoricalRecord->percent_change = $percentageChange;
	        			$previousHistoricalRecord->day_change = $dayChange;
	        			$previousHistoricalRecord->save();

	        			$currentHistoricalRecord->market_cap = $previousHistoricalRecord->market_cap*(1-($previousHistoricalRecord->percent_change/100));
	        			$currentHistoricalRecord->save();
	        		}
	        		//Set current date to previous date for next iteration
	        		$previousDate = $date;
	        	}

	        	$this->info('Completed: '.$stockCode. ' '.round((100/$numberOfStocks)*($stockKey+1), 2).'%');
	        }
	    }
    }
}
