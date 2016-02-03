<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketIndexHistoricalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_index_historicals', function(Blueprint $table){
            $table->increments('id');
            $table->string('index_name');
            $table->date('date');
            $table->decimal('total_index_market_cap', 10, 2);
            $table->decimal('day_change', 6, 2);
            $table->double('volume');
            $table->double('EBITDA');
            $table->decimal('earnings_per_share_current', 8, 2);
            $table->decimal('earnings_per_share_next_year', 8, 2);
            $table->decimal('price_to_earnings', 8, 2);
            $table->decimal('price_to_book', 8, 2);
            $table->decimal('dividend_yield', 8, 2);
            $table->decimal('average_index_market_cap', 10, 2);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('market_index_historicals');
    }
}
