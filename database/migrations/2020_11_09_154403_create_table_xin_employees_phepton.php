<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableXinEmployeesPhepton extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees_phepton', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->integer('leave_type_id');
            $table->integer('grant_of_number');
            $table->integer('used_of_number');
            $table->integer('remain_of_number');
            $table->year('year');
            $table->date('expiration_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees_phepton');
    }
}
