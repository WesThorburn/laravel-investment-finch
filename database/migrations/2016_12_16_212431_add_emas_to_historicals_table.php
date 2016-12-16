<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmasToHistoricalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historicals', function (Blueprint $table) {
            $table->decimal('twelve_day_ema', '7', '3')->after('two_hundred_day_moving_average')->nullable();
            $table->decimal('twenty_six_day_ema', '7', '3')->after('twelve_day_ema')->nullable();
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
            $table->dropColumn('twelve_day_ema');
            $table->dropColumn('twenty_six_day_ema');
        });
    }
}
