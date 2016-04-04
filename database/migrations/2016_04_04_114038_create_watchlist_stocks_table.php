<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWatchlistStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('watchlist_stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('watchlist_id')->unsigned();
            $table->char('stock_code', 3);
            $table->timestamps();
            $table->foreign('watchlist_id')->references('id')->on('watchlists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('watchlist_stocks');
    }
}
