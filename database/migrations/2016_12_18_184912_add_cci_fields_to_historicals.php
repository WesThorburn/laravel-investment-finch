<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCciFieldsToHistoricals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historicals', function (Blueprint $table) {
            $table->decimal('typical_price', '7', '3')->after('five_day_rsi')->nullable();
            $table->decimal('cci', '7', '3')->after('typical_price')->nullable();
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
            $table->dropColumn('typical_price');
            $table->dropColumn('cci');
        });
    }
}
