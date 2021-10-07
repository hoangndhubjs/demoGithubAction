<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnqBusinessSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_settings', function (Blueprint $table) {
            $table->unique(['business_id', 'option'], 'unq_option_by_business');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_settings', function (Blueprint $table) {
            $table->dropUnique('unq_option_by_business');
        });
    }
}
