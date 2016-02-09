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
            $historicals = Historicals::where('date', '2016-02-08')->get();
            $numberOfStocks = $historicals->count();
            foreach($historicals as $key => $historical){
                $historicalSheetUrl = "http://real-chart.finance.yahoo.com/table.csv?s=".$historical->stock_code.".AX&d=1&e=9&f=2016&g=d&a=1&b=8&c=2016&ignore=.csv";
                if(get_headers($historicalSheetUrl, 1)[0] == 'HTTP/1.1 200 OK')
                {
                    file_put_contents('database/files/spreadsheet.txt', trim(str_replace("Date,Open,High,Low,Close,Volume,Adj Close", "", file_get_contents($historicalSheetUrl))));
                    $spreadSheetFile = fopen('database/files/spreadsheet.txt', 'r');
                    $dailyTradeRecord = array();
                    $line = fgets($spreadSheetFile);
                    $pieces = explode(',', $line);

                	$historical->open = $pieces[1];
                    $historical->high = $pieces[2];
                    $historical->low = $pieces[3];
                    $historical->close = $pieces[4];
                    $historical->volume = $pieces[5];
                    $historical->adj_close = $pieces[6];
                    $historical->save();
                }
                $this->info("Completed: ".$historical->stock_code." ".($key+1)."/".$numberOfStocks." - ".round(($key+1)*(100/$numberOfStocks), 2)."%");
            }
            $this->info("The historical financials have been downloaded.");
        }
    }
}
