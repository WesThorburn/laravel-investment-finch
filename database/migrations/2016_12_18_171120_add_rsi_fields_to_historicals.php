<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRsiFieldsToHistoricals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historicals', function (Blueprint $table) {
            $table->decimal('five_day_rsi', '7', '3')->after('obv')->nullable();
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
            $table->dropColumn('five_day_rsi');
        });
    }
}
