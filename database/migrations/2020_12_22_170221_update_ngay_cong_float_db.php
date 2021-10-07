<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNgayCongFloatDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees_tmp_payslip', function (Blueprint $table) {
            $table->float('ngay_cong', 4, 2, true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees_tmp_payslip', function (Blueprint $table) {
            $table->integer('ngay_cong')->change();
        });
    }
}
