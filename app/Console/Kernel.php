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
		'App\Console\Commands\GetHistoricalFinancialsCommand',
		'App\Console\Commands\GetDailyFinancialsCommand',
		'App\Console\Commands\ResetDayChangeCommand'
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('stocks:updateStockMetrics')->withoutOverlapping();
		$schedule->command('stocks:getDailyFinancials')->dailyAt('06:45');
		$schedule->command('stocks:stocks:resetDayChange')->dailyAt('14:00');
		$schedule->command('stocks:updateStockList')->dailyAt('15:00');

	}

}
