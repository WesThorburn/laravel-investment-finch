<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAsx2050200ToStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->boolean('asx_20')->after('business_summary');
            $table->boolean('asx_50')->after('asx_20');
            $table->boolean('asx_200')->after('asx_50');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn('asx_20');
            $table->dropColumn('asx_50');
            $table->dropColumn('asx_200');
        });
    }
}
