<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMetricsColumnsToSectorHistoricals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sector_historicals', function (Blueprint $table) {
            $table->double('average_daily_volume')->nullable()->after('day_change');
            $table->double('EBITDA')->nullable()->after('average_daily_volume');
            $table->decimal('earnings_per_share_current', 8, 2)->nullable()->after('EBITDA');
            $table->decimal('earnings_per_share_next_year', 8, 2)->nullable()->after('earnings_per_share_current');
            $table->decimal('price_to_earnings', 8, 2)->nullable()->after('earnings_per_share_next_year');
            $table->decimal('price_to_book', 8, 2)->nullable()->after('price_to_earnings');
            $table->decimal('dividend_yield', 8, 2)->nullable()->after('price_to_book');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sector_historicals', function (Blueprint $table) {
            $table->dropColumn('average_daily_volume');
            $table->dropColumn('EBITDA');
            $table->dropColumn('earnings_per_share_current');
            $table->dropColumn('earnings_per_share_next_year');
            $table->dropColumn('price_to_earnings');
            $table->dropColumn('price_to_book');
            $table->dropColumn('dividend_yield');
        });
    }
}
