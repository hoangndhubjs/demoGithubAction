<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnLeaveTpye extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_type', function (Blueprint $table) {
            $table->string('days_per_month')->nullable();
            $table->string('days_per_week')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_type', function (Blueprint $table) {
            $table->dropColumn('days_per_month');
            $table->dropColumn('days_per_week');
        });
    }
}
