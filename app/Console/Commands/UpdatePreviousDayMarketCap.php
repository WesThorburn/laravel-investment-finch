<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Stock;
use App\Models\StockMetrics;

class UpdatePreviousDayMarketCap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:updatePreviousDayMarketCap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Updates yesterday's market cap with most recent data.";

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
        $stockCodes = Stock::lists('stock_code');
        foreach($stockCodes as $stockCode){
            $metric = StockMetrics::where('stock_code', $stockCode)->first();
            $metric->yesterdays_market_cap = $metric->current_market_cap;
            $metric->save();
        }
    }
}
