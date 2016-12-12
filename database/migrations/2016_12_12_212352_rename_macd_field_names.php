<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameMacdFieldNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historicals', function (Blueprint $table) {
            $table->renameColumn('macd_12', 'macd_line');
            $table->renameColumn('macd_26', 'signal_line');
            $table->renameColumn('macd_9', 'macd_histogram');
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
            $table->renameColumn('macd_line', 'macd_12');
            $table->renameColumn('macd_line', 'macd_26');
            $table->renameColumn('macd_line', 'macd_9');
        });
    }
}
