<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStochasticsToHistoricals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historicals', function (Blueprint $table) {
            $table->decimal('stochastic_k', '7', '3')->after('macd_histogram')->nullable();
            $table->decimal('stochastic_d', '7', '3')->after('stochastic_k')->nullable();
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
            $table->dropColumn('stochastic_k');
            $table->dropColumn('stochastic_d');
        });
    }
}
