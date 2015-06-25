<?php

namespace App\Console\Commands;

use App\Models\StockMetrics;
use Illuminate\Console\Command;

class ResetDayChangeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:resetDayChange';

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
        $this->info("Resetting day gain to 0.00% for all stocks.");
        StockMetrics::where('day_change', '!=', 0)->update(['day_change' => 0.00]);
        $this->info("All gains have been reset.");
    }
}
