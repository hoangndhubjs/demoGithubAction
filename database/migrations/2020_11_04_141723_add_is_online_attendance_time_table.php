<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsOnlineAttendanceTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_time', function (Blueprint $table) {
            $table->tinyInteger('is_online')->default(0)->comment("0: offline, 1: online");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_time', function (Blueprint $table) {
            $table->dropColumn('is_online');
        });
    }
}
