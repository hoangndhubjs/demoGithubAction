<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarrantyHistoryAsset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warranty_history_asset', function (Blueprint $table) {
            $table->bigIncrements('warranty_id');
            $table->integer('asset_id');
            $table->date('warranty_start');
            $table->date('warranty_end')->nullable();
            $table->longText('warranty_note');
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
        Schema::dropIfExists('warranty_history_asset');
    }
}
