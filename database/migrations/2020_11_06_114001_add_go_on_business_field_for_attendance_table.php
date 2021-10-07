<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoOnBusinessFieldForAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_daily', function (Blueprint $table) {
            $table->tinyInteger('is_go_on_business')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_daily', function (Blueprint $table) {
            $table->dropColumn('is_go_on_business');
        });
    }
}
