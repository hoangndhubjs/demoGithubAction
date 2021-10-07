<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hrsale_notificaions', function (Blueprint $table) {
            $table->string('url')->nullable();
            $table->string('mini_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hrsale_notificaions', function (Blueprint $table) {
            $table->dropColumn('url');
            $table->dropColumn('mini_title');
        });
    }
}
