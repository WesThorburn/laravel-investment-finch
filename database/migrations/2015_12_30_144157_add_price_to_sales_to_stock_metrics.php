<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriceToSalesToStockMetrics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_metrics', function (Blueprint $table) {
            $table->decimal('price_to_sales', 8, 2)->nullable()->after('price_to_earnings');
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
            $table->dropColumn('price_to_sales');
        });
    }
}
