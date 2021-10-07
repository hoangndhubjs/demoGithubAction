<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTableContactToMakeAColumnsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_contacts', function (Blueprint $table) {
            $table->integer('is_primary')->nullable()->change();
            $table->integer('is_dependent')->nullable()->change();
            $table->string('work_phone')->nullable()->change();
            $table->string('work_phone_extension')->nullable()->change();
            $table->string('home_phone')->nullable()->change();
            $table->string('personal_email')->nullable()->change();
            $table->mediumtext('address_1')->nullable()->change();
            $table->mediumtext('address_2')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('state')->nullable()->change();
            $table->string('zipcode')->nullable()->change();
            $table->string('country')->nullable()->change();
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
