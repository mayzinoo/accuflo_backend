<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('station_id');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('shelf_id')->nullable();
            $table->unsignedBigInteger('period_id');
            $table->unsignedBigInteger('user_id');
            $table->string('unit_id');
            $table->float('weight');
            $table->unsignedBigInteger('package_id');
            $table->string('size');
            $table->boolean('already_updated')->nullable(); 
            $table->dateTime('mobile_submit_time')->nullable(); // mobile submit time
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
        Schema::dropIfExists('weights');
    }
}
