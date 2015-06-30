<?php

namespace App\Console\Commands;

use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetDailyFinancialsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:getDailyFinancials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

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
        if(Carbon::now()->isWeekDay()){
            $this->info("This process can take up to an hour...");
            $this->info("Getting daily financials...");
            $stockCodes = Stock::all()->lists('stock_code');
            $numberOfStocks = count($stockCodes);
            foreach($stockCodes as $key => $stockCode){
                $dailyFinancialsUrl = "http://real-chart.finance.yahoo.com/table.csv?s=".$stockCode.".AX&d=".(date('m')-1)."&e=".date('d')."&f=".date('Y')."&g=d&a=".(date('m')-1)."&b=".date('d')."&c=".date('Y')."&ignore=.csv";
                if(get_headers($dailyFinancialsUrl, 1)[0] == 'HTTP/1.1 200 OK'){
                    file_put_contents('database/files/spreadsheet.txt', trim(str_replace("Date,Open,High,Low,Close,Volume,Adj Close", "", file_get_contents($dailyFinancialsUrl))));
                    $spreadSheetFile = fopen('database/files/spreadsheet.txt', 'r');
                    $dailyTradeRecords = array();
                    while(!feof($spreadSheetFile)){
                        $line = fgets($spreadSheetFile);
                        $pieces = explode(',', $line);
                        if(Carbon::createFromFormat('Y-m-d', $pieces[0])->isToday()){
                            array_push($dailyTradeRecords, array(
                                'stock_code' => $stockCode,
                                'date' => $pieces[0],
                                'open' => $pieces[1],
                                'high' => $pieces[2],
                                'low' => $pieces[3],
                                'close' => $pieces[4],
                                'volume' => $pieces[5],
                                'adj_close' => $pieces[6]
                            ));
                        }
                    }
                    \DB::table('historicals')->insert($dailyTradeRecords);
                }
                $this->info("Getting daily financials...".round(($key+1)*(100/$numberOfStocks), 2)."%");
            }
            $this->info("Finished getting daily financials for ".$numberOfStocks. " stocks.");
        }
        else{
            $this->info("This command can only be run on weekdays");
        }
        
    }
}
