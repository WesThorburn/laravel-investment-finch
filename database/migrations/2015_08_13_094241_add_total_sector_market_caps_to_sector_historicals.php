<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalSectorMarketCapsToSectorHistoricals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sector_historicals', function (Blueprint $table) {
            $table->decimal('total_sector_market_cap', 8, 2)->after('date');
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
            $table->dropColumn('total_sector_market_cap');
        });
    }
}
