<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumUploadfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advance_salaries', function (Blueprint $table) {
            $table->string('file_upload')->after('is_deducted_from_salary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advance_salaries', function (Blueprint $table) {
            $table->dropColumn('file_upload');
        });
    }
}
