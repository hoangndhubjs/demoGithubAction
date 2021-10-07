<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFingerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_finger', function (Blueprint $table) {
            $table->id();
            $table->integer('uid');
            $table->string('user_id');
            $table->string('name');
            $table->integer('privilege');
            $table->string('password')->nullable();
            $table->string('group_id')->nullable();
            $table->bigInteger('card');
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
        Schema::dropIfExists('user_finger');
    }
}
