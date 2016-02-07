<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalMetricsToStockMetricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_metrics', function (Blueprint $table) {
            $table->decimal('open', 7, 3);
            $table->decimal('high', 7, 3);
            $table->decimal('low', 7, 3);
            $table->decimal('close', 7, 3);
            $table->decimal('adj_close', 7, 3);
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
            $table->dropColumn('open');
            $table->dropColumn('high');
            $table->dropColumn('low');
            $table->dropColumn('close');
            $table->dropColumn('adj_close');
        });
    }
}
