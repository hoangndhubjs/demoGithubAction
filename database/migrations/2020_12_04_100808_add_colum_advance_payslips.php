<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumAdvancePayslips extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_payslips', function (Blueprint $table) {
            $table->string('total_advance')->after('total_salary_formal');
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
            $table->dropColumn('total_advance');
        });
    }
}
