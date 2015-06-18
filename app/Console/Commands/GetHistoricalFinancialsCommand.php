<?php

namespace App\Console\Commands;

use App\Models\Stock;
use Illuminate\Console\Command;

class GetHistoricalFinancialsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:getHistoricalFinancials';

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
        $this->info("Downloading historical financials... This may take several minutes...");
        foreach(Stock::lists('stock_code') as $stockCode){
            $historicalSheetUrl = "http://real-chart.finance.yahoo.com/table.csv?s=".$stockCode.".AX&d=".date('m')."&e=".date('d')."&f=".date('Y')."&g=d&a=1&b=1&c=2000&ignore=.csv";
            if(get_headers($historicalSheetUrl, 1)[0] == 'HTTP/1.1 200 OK')
            {
                $spreadSheetRows = explode(PHP_EOL, file_get_contents($historicalSheetUrl));
                $this->info($spreadSheetRows[0]);

                foreach($spreadSheetRows as $row){
                    //$this->info($row);
                    $this->info("Finished: ".$stockCode);
                }
            }
            break;
        }
        $this->info("The historical financials have been downloaded.");
    }
}
