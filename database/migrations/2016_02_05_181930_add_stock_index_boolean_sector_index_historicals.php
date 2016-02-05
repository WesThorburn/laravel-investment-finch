<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStockIndexBooleanSectorIndexHistoricals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sector_index_historicals', function (Blueprint $table) {
            $table->boolean('stock_index')->after('sector');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sector_index_historicals', function (Blueprint $table) {
            $table->dropColumn('stock_index');
        });
    }
}
