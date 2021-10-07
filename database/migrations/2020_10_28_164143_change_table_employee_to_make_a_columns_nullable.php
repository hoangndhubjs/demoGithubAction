<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTableEmployeeToMakeAColumnsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('contact_no', 200)->nullable()->change();
            $table->string('date_of_birth')->nullable()->change();
            $table->string('gender', 200)->nullable()->change();
            $table->string('marital_status', 200)->nullable()->change();
            $table->mediumText('address')->nullable()->change();
            $table->mediumText('facebook_link')->nullable()->change();
            $table->mediumText('twitter_link')->nullable()->change();
            $table->mediumText('blogger_link')->nullable()->change();
            $table->mediumText('youtube_link')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('employees');
    }
}
