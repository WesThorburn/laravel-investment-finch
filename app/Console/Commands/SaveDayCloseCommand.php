<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StockMetrics;
use App\Models\Stock;
use Carbon\Carbon;

class SaveDayCloseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:saveDayClose';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds the close price of each stock to the historical financials.';

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
        $this->info("Updating the historicals with today's close price.");
        

        $this->info("Updating complete.");
    }
}
