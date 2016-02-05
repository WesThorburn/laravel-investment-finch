<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire',
		'App\Console\Commands\UpdateStockListCommand',
		'App\Console\Commands\UpdateStockMetricsCommand',
		'App\Console\Commands\FillHistoricalFinancialsCommand',
		'App\Console\Commands\GetDailyFinancialsCommand',
		'App\Console\Commands\ResetDayChangeCommand',
		'App\Console\Commands\UpdateSectorMetricsCommand',
		'App\Console\Commands\CalculateStockChangeCommand',
		'App\Console\Commands\FillSectorIndexHistoricalsCommand',
		'App\Console\Commands\GetCompanySummariesCommand',
		'App\Console\Commands\FillHistoricalMovingAveragesCommand',
		'App\Console\Commands\CalculateTrendCommand',
		'App\Console\Commands\GetASXListsCommand',
		'App\Console\Commands\UpdateStockAnalysisCommand'
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		if(isTradingDay()){
			$schedule->command('stocks:calculateStockChange')->weekdays()->dailyAt('03:35');
			$schedule->command('stocks:calculateTrend')->dailyAt('02:15');
			$schedule->command('stocks:updateStockAnalysis')->dailyAt('02:30');
			$schedule->command('stocks:resetDayChange')->dailyAt('04:00');
			//Full sector metric update once before and once after each trading day
			$schedule->command('stocks:updateSectorMetrics', ['--mode' => 'full'])->weekdays()->dailyAt('10:28');
			$schedule->command('stocks:updateSectorMetrics', ['--mode' => 'full'])->weekdays()->dailyAt('16:25');
			$schedule->command('stocks:getDailyFinancials')->weekdays()->dailyAt('16:30');
		}
		
      	//Only run these between 10:30 and 17:00 Sydney Time
		if(getCurrentTimeIntVal() >= 103000 && getCurrentTimeIntVal() <= 170000 && isTradingDay()){
			$schedule->command('stocks:updateStockMetrics')->cron("*/2 * * * *");
			$schedule->command('stocks:updateSectorMetrics', ['--mode' => 'partial'])->cron("*/2 * * * *");
		}

		$schedule->command('stocks:updateStockList')->dailyAt('02:00');
		$schedule->command('stocks:getCompanySummaries')->dailyAt('02:05');
	}
}
