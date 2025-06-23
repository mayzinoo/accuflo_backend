<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFullCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('full_counts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->string('size');
            $table->unsignedBigInteger('period_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('station_id');
            $table->unsignedBigInteger('period_count');
            $table->unsignedBigInteger('package_id');
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
        Schema::dropIfExists('full_counts');
    }
}
