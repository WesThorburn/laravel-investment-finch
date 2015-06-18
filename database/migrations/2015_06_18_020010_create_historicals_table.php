<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historicals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stock_code');
            $table->date('date');
            $table->decimal('open', '7', '3');
            $table->decimal('high', '7', '3');
            $table->decimal('low', '7', '3');
            $table->decimal('close', '7', '3');
            $table->bigInteger('volume');
            $table->decimal('adj_close', '7', '3');
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
        Schema::drop('historicals');
    }
}
