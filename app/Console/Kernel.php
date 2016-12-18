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
		'App\Console\Commands\FillHistoricalMACDsCommand',
		'App\Console\Commands\FillHistoricalEMAsCommand',
		'App\Console\Commands\FillHistoricalStochasticsCommand',
		'App\Console\Commands\FillHistoricalOBVCommand',
		'App\Console\Commands\CalculateTrendCommand',
		'App\Console\Commands\GetASXListsCommand',
		'App\Console\Commands\UpdateStockAnalysisCommand',
		'App\Console\Commands\UpdateIndexMetricsCommand',
		'App\Console\Commands\BackfillMarketCapData',
		'App\Console\Commands\BackfillSectorCapData',
		'App\Console\Commands\UpdatePreviousDayMarketCap'
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
			$schedule->command('stocks:updateStockList')->dailyAt('02:00');
			$schedule->command('stocks:getCompanySummaries')->dailyAt('02:05');
			$schedule->command('stocks:calculateTrend')->dailyAt('02:15');
			$schedule->command('stocks:updateStockAnalysis')->dailyAt('02:30');
			$schedule->command('stocks:calculateStockChange')->weekdays()->dailyAt('02:45');
			$schedule->command('stocks:updatePreviousDayMarketCap')->dailyAt('05:55');
			$schedule->command('stocks:resetDayChange')->dailyAt('06:00');
			$schedule->command('stocks:updateSectorMetrics', ['--mode' => 'full'])->weekdays()->dailyAt('10:28');
			$schedule->command('stocks:updateIndexMetrics', ['--mode' => 'full'])->weekdays()->dailyAt('10:35');
			if(getCurrentTimeIntVal() >= 103000 && getCurrentTimeIntVal() <= 163000){
				$schedule->command('stocks:updateStockMetrics')->everyThirtyMinutes();
				$schedule->command('stocks:updateSectorMetrics', ['--mode' => 'partial'])->everyThirtyMinutes();
				$schedule->command('stocks:updateIndexMetrics', ['--mode' => 'partial'])->everyThirtyMinutes();
			}
			$schedule->command('stocks:updateSectorMetrics', ['--mode' => 'full'])->weekdays()->dailyAt('16:35');
			$schedule->command('stocks:updateIndexMetrics', ['--mode' => 'full'])->weekdays()->dailyAt('16:40');
			$schedule->command('stocks:getDailyFinancials')->weekdays()->dailyAt('16:45');
		}
	}
}
