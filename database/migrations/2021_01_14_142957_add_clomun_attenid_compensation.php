<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClomunAttenidCompensation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compensations', function (Blueprint $table) {
            $table->integer('attendance_id')->after('comment_compensation')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compensations', function (Blueprint $table) {
            $table->dropColumn('attendance_id');
        });
    }
}
