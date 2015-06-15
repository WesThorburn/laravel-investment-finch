<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDayGainToMetricsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('stock_metrics', function(Blueprint $table)
		{
			$table->decimal('day_change', 6, 3)->after('last_trade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('stock_metrics', function(Blueprint $table)
		{
			$table->dropColumn('day_change');
		});
	}

}
