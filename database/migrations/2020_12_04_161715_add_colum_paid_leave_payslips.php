<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumPaidLeavePayslips extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_payslips', function (Blueprint $table) {
            $table->integer('paid_leave')->default(0)->after('total_advance');
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
            $table->dropColumn('paid_leave');
        });
    }
}
