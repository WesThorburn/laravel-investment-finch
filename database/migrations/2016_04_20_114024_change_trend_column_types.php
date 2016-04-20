<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTrendColumnTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_metrics', function (Blueprint $table) {
            $table->decimal('trend_short_term', 5, 2)->change();
            $table->decimal('trend_medium_term', 5, 2)->change();
            $table->decimal('trend_long_term', 5, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_metrics', function (Blueprint $table) {
            $table->string('trend_short_term')->change();
            $table->string('trend_medium_term')->change();
            $table->string('trend_long_term')->change();
        });
    }
}
