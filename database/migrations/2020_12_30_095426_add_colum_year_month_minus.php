<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumYearMonthMinus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('money_minus', function (Blueprint $table) {
            $table->string('year_month', 100)->after('option_minus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('money_minus', function (Blueprint $table) {
            $table->dropColumn('year_month');
        });
    }
}
