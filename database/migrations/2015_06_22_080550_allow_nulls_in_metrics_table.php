<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowNullsInMetricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_metrics', function (Blueprint $table) {
            $table->decimal('last_trade', 8, 2)->nullable()->change();
            $table->double('average_daily_volume')->nullable()->change();
            $table->double('EBITDA')->nullable()->change();
            $table->decimal('earnings_per_share_current', 8, 2)->nullable()->change();
            $table->decimal('earnings_per_share_next_year', 8, 2)->nullable()->change();
            $table->decimal('price_to_earnings', 8, 2)->nullable()->change();
            $table->decimal('price_to_book', 8, 2)->nullable()->change();
            $table->decimal('year_high', 8, 2)->nullable()->change();
            $table->decimal('year_low', 8, 2)->nullable()->change();
            $table->decimal('fifty_day_moving_average', 8, 2)->nullable()->change();
            $table->decimal('two_hundred_day_moving_average', 8, 2)->nullable()->change();
            $table->string('market_cap')->nullable()->change();
            $table->decimal('dividend_yield', 8, 2)->nullable()->change();
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
            //
        });
    }
}
