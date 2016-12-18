<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddObvToHistoricals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historicals', function (Blueprint $table) {
            $table->bigInteger('obv')->after('stochastic_d')->nullable();
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
            $table->dropColumn('obv');
        });
    }
}
