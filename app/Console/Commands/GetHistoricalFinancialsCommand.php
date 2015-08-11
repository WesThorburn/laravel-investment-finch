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
        $this->info("This is involves downloading and storing several million records. This may take several hours...");
        if($this->confirm('Do you wish to continue?'))
        {
            $this->info("Downloading historical financials...");
            $numberOfStocks = Stock::count();
            foreach(Stock::lists('stock_code') as $key => $stockCode){
                $historicalSheetUrl = "http://real-chart.finance.yahoo.com/table.csv?s=".$stockCode.".AX&d=7&e=12&f=2015&g=d&a=7&b=11&c=2015&ignore=.csv";
                if(get_headers($historicalSheetUrl, 1)[0] == 'HTTP/1.1 200 OK')
                {
                    file_put_contents('database/files/spreadsheet.txt', trim(str_replace("Date,Open,High,Low,Close,Volume,Adj Close", "", file_get_contents($historicalSheetUrl))));
                    $spreadSheetFile = fopen('database/files/spreadsheet.txt', 'r');
                    $dailyTradeRecord = array();
                    while(!feof($spreadSheetFile)){
                        $line = fgets($spreadSheetFile);
                        $pieces = explode(',', $line);
                        array_push($dailyTradeRecord, array(
                            'stock_code' => $stockCode,
                            'date' => $pieces[0],
                            'open' => $pieces[1],
                            'high' => $pieces[2],
                            'low' => $pieces[3],
                            'close' => $pieces[4],
                            'volume' => $pieces[5],
                            'adj_close' => $pieces[6],
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s")
                        ));
                    }
                    //\DB::table('historicals')->where('stock_code', $stockCode)->delete();
                    \DB::table('historicals')->insert($dailyTradeRecord);
                }
                $this->info("Completed: ".$stockCode." ".($key+1)."/".$numberOfStocks." - ".round(($key+1)*(100/$numberOfStocks), 2)."%");
            }
            $this->info("The historical financials have been downloaded.");
        }
    }
}
