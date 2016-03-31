<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenamePortfolioStocksTableHeadings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('portfolio_stocks', function (Blueprint $table) {
            $table->renameColumn('purchase_qty', 'quantity');
            $table->dropColumn('purchase_date');
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
            $table->renameColumn('quantity', 'purchase_qty');
            $table->date('purchase_date');
        });
    }
}
