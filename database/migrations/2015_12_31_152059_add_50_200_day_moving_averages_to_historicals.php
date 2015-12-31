<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add50200DayMovingAveragesToHistoricals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historicals', function (Blueprint $table) {
            $table->decimal('fifty_day_moving_average', '7', '3')->after('adj_close');
            $table->decimal('two_hundred_day_moving_average', '7', '3')->after('fifty_day_moving_average');
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
            $table->dropColumn('fifty_day_moving_average');
            $table->dropColumn('two_hundred_day_moving_average');
        });
    }
}
