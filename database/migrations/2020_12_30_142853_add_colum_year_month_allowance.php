<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumYearMonthAllowance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_allowances', function (Blueprint $table) {
            $table->string('year_month', 100)->after('allowance_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_allowances', function (Blueprint $table) {
            $table->dropColumn('option_minus');
        });
    }
}
