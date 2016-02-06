<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionMetricsToHistoricals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historicals', function (Blueprint $table) {
            $table->decimal('EBITDA', 8, 2)->after('two_hundred_day_moving_average');
            $table->decimal('earnings_per_share_current', 8, 2)->after('EBITDA');
            $table->decimal('earnings_per_share_next_year', 8, 2)->after('earnings_per_share_current');
            $table->decimal('price_to_earnings', 8, 2)->after('earnings_per_share_next_year');
            $table->decimal('price_to_sales', 8, 2)->after('price_to_earnings');
            $table->decimal('price_to_book', 8, 2)->after('price_to_sales');
            $table->decimal('year_high', 8, 2)->after('price_to_book');
            $table->decimal('year_low', 8, 2)->after('year_high');
            $table->decimal('market_cap', 8, 2)->after('year_low');
            $table->decimal('dividend_yield', 8, 2)->after('market_cap');
            $table->string('trend_short_term')->after('dividend_yield');
            $table->string('trend_medium_term')->after('trend_short_term');
            $table->string('trend_long_term')->after('trend_medium_term');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historicals', function (Blueprint $table) {
            $table->dropColumn('EBITDA');
            $table->dropColumn('earnings_per_share_current');
            $table->dropColumn('earnings_per_share_next_year');
            $table->dropColumn('price_to_earnings');
            $table->dropColumn('price_to_sales');
            $table->dropColumn('price_to_book');
            $table->dropColumn('year_high');
            $table->dropColumn('year_low');
            $table->dropColumn('market_cap');
            $table->dropColumn('dividend_yield');
            $table->dropColumn('trend_short_term');
            $table->dropColumn('trend_medium_term');
            $table->dropColumn('trend_long_term');
        });
    }
}
