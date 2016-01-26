<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrendColumnsToMetricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_metrics', function (Blueprint $table) {
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
        Schema::table('stock_metrics', function (Blueprint $table) {
            $table->dropColumn('trend_short_term');
            $table->dropColumn('trend_medium_term');
            $table->dropColumn('trend_long_term');
        });
    }
}
