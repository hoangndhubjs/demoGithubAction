<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTotalHolidaysTrail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_payslips', function (Blueprint $table) {
            $table->integer('total_holidays_trail_work')->after('total_holidays')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_payslips', function (Blueprint $table) {
            $table->dropColumn('total_holidays_trail_work');
        });
    }
}
