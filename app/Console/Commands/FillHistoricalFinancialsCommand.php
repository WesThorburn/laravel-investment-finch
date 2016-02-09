<?php

namespace App\Console\Commands;

use App\Models\Historicals;
use App\Models\Stock;
use Illuminate\Console\Command;

class FillHistoricalFinancialsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:fillHistoricalFinancials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads historical financials for all stocks in the stocks table.';

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
    	$this->info("This is involves downloading and storing several million records. This may take several hours...");
        if($this->confirm('Do you wish to continue?'))
        {
            $this->info("Downloading historical financials...");
            $historicals = Historicals::where(['date' => '2016-02-08', 'close' => 0.000])->get();
            $numberOfStocks = $historicals->count();
            foreach($historicals as $key => $historical){
            	$this->info("Loading: ".$historical->stock_code);
        		$yesterdays = Historicals::where(['stock_code' => $historical->stock_code, 'date' => '2016-02-05'])->first();

        		$historical = Historicals::where(['stock_code' => $historical->stock_code, 'date' => '2016-02-08'])->first();

            	$historical->open = $yesterdays->open;
                $historical->high = $yesterdays->high;
                $historical->low = $yesterdays->low;
                $historical->close = $yesterdays->close;
                $historical->volume = $yesterdays->volume;
                $historical->adj_close = $yesterdays->adj_close;
                $historical->save();
                $this->info("Completed: ".$historical->stock_code." ".($key+1)."/".$numberOfStocks." - ".round(($key+1)*(100/$numberOfStocks), 2)."%");
            }
            $this->info("The historical financials have been downloaded.");
        }
    }
}
