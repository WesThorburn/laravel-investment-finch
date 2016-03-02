<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Stock;

class GetCompanySummariesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:getCompanySummaries {--testMode=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Locates and stores all available company summaries for each stock code in the stock table.';

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
        $this->info("Locating and storing company summaries...");
        $stocks = Stock::lists('stock_code');

        if($this->option('testMode') == 'true'){
            $this->info("[Test Mode]");
            $stocks = ['CBA', 'TLS'];
        }
        
        $numberOfStocks = count($stocks);
        foreach($stocks as $key => $stockCode){
            //Check if there is already a summary
            if(Stock::where('stock_code', $stockCode)->pluck('business_summary') == null){
                if($pageContents = @file_get_contents("https://au.finance.yahoo.com/q/pr?s=".$stockCode.".AX")){
                    if(strpos($pageContents, "</th></tr></table><p>") && strpos($pageContents, '</p><p><a href="/q/ks?s='.$stockCode.'.AX"><b>')){
                        $businessSummary = explode('</p><p><a href="/q/ks?s='.$stockCode.'.AX"><b>', explode("</th></tr></table><p>", $pageContents)[1]);
                        if(strlen($businessSummary[0]) > 0){
                            $stock = Stock::where('stock_code', $stockCode)->first();
                            $stock->business_summary = $businessSummary[0];
                            $stock->save();
                        }
                    }
                }
            }
            $this->info("Completed: ".$stockCode." ".($key+1)."/".$numberOfStocks." - ".round(($key+1)*(100/$numberOfStocks), 2)."%");
        }
    }
}
