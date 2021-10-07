<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTableImmigrationToMakeAColumnsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_immigration', function (Blueprint $table) {
            $table->string('expiry_date')->nullable()->change();
            $table->string('document_number')->nullable()->change();
            $table->string('country_id')->nullable()->change();
            $table->string('eligible_review_date')->nullable()->change();
            $table->mediumtext('comments')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('make_a_columns_nullable', function (Blueprint $table) {
//            //
//        });
    }
}
