<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveMovingAveragesFromMetricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_metrics', function (Blueprint $table) {
            $table->dropColumn('fifty_day_moving_average');
            $table->dropColumn('two_hundred_day_moving_average');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_metrics', function (Blueprint $table) {
            $table->decimal('fifty_day_moving_average', 8, 2)->nullable()->after('year_low');
            $table->decimal('two_hundred_day_moving_average', 8, 2)->nullable()->after('fifty_day_moving_average');
        });
    }
}
