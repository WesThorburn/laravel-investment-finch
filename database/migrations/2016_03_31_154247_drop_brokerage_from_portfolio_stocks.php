<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropBrokerageFromPortfolioStocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('portfolio_stocks', function (Blueprint $table) {
            $table->dropColumn('brokerage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('portfolio_stocks', function (Blueprint $table) {
            $table->decimal('brokerage', 8, 3)->nullable();
        });
    }
}
