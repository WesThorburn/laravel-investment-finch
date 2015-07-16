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
		'App\Console\Commands\ResetDayChangeCommand',
		'App\Console\Commands\UpdateSectorChangeCommand',
		'App\Console\Commands\CalculateStockChangeCommand'
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
      	//Only run these between 10:00 and 17:00 Sydney Time
		if(getCurrentTimeIntVal() >= 103000 && getCurrentTimeIntVal() <= 170000){
			$schedule->command('stocks:updateStockMetrics')->weekdays()->withoutOverlapping();
			$schedule->command('stocks:updateSectorChange')->weekdays()->withoutOverlapping();
		}
    	
    	$schedule->command('stocks:calculateStockChange')->weekdays()->dailyAt('16:30');
		$schedule->command('stocks:getDailyFinancials')->weekdays()->dailyAt('16:30');
		$schedule->command('stocks:resetDayChange')->dailyAt('00:00');
		$schedule->command('stocks:updateStockList')->dailyAt('02:00');

	}
}
