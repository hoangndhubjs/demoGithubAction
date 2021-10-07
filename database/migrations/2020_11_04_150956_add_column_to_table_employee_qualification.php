<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTableEmployeeQualification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_qualification', function (Blueprint $table) {
            $table->string('majors')->nullable();
            $table->string('document_file');
            $table->integer('language_id')->nullable()->change();
            $table->string('from_year')->nullable()->change();
            $table->string('to_year')->nullable()->change();
            $table->mediumtext('skill_id')->nullable()->change();
            $table->mediumtext('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_qualification', function (Blueprint $table) {
            $table->dropColumn('majors');
            $table->dropColumn('document_file');
        });
    }
}
