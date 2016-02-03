<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSectorAverageDailyVolumeToVolume extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sector_historicals', function (Blueprint $table) {
            $table->renameColumn('average_daily_volume', 'volume');
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
            $table->renameColumn('volume', 'average_daily_volume');
        });
    }
}
