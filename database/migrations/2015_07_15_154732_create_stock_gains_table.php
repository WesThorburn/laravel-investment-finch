<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockGainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_gains', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stock_code');
            $table->decimal('week_change', 10, 2);
            $table->decimal('two_week_change', 10, 2);
            $table->decimal('month_change', 10, 2);
            $table->decimal('two_month_change', 10, 2);
            $table->decimal('three_month_change', 10, 2);
            $table->decimal('six_month_change', 10, 2);
            $table->decimal('year_change', 10, 2);
            $table->decimal('two_year_change', 10, 2);
            $table->decimal('three_year_change', 10, 2);
            $table->decimal('five_year_change', 10, 2);
            $table->decimal('ten_year_change', 10, 2);
            $table->decimal('all_time_change', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stock_gains');
    }
}
