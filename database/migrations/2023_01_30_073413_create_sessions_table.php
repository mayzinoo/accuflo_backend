<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('station_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('item_size_id');
            $table->unsignedBigInteger('item_package_id');
            $table->integer('current_period_count')->nullable();
            $table->float('current_period_weight')->nullable();
            $table->dateTime('submit_time')->nullable();
            $table->integer('shelf')->nullable();
            $table->string('section')->nullable();
            $table->string('unit')->nullable();

            $table->string('device')->nullable();
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
        Schema::dropIfExists('sessions');
    }
}
