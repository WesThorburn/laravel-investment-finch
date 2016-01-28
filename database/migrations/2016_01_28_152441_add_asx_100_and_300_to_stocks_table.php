<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAsx100And300ToStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->boolean('asx_100')->after('asx_50');
            $table->boolean('asx_300')->after('asx_200');
            $table->boolean('all_ords')->after('asx_300');
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
            $table->dropColumn('asx_100');
            $table->dropColumn('asx_300');
            $table->dropColumn('all_ords');
        });
    }
}
