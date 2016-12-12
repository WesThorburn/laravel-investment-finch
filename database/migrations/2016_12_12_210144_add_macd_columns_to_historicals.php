<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMacdColumnsToHistoricals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historicals', function (Blueprint $table) {
            $table->decimal('macd_12', '7', '3')->after('two_hundred_day_moving_average')->nullable();
            $table->decimal('macd_26', '7', '3')->after('macd_12')->nullable();
            $table->decimal('macd_9', '7', '3')->after('macd_26')->nullable();
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
            $table->dropColumn('macd_12');
            $table->dropColumn('macd_26');
            $table->dropColumn('macd_9');
        });
    }
}
