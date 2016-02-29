<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPegRatioToSectorIndexHistoricals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sector_index_historicals', function (Blueprint $table) {
            $table->decimal('peg_ratio', 8, 2)->after('price_to_book')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sector_index_historicals', function (Blueprint $table) {
            $table->dropColumn('peg_ratio');
        });
    }
}
