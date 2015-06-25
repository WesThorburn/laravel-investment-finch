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
		'App\Console\Commands\SaveDayCloseCommand'
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('stocks:updateStockList')->dailyAt('15:00');
		$schedule->command('stocks:saveDayClose')->dailyAt('06:00');
		$schedule->command('stocks:updateStockMetrics')->withoutOverlapping();
	}

}
