<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchmixesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batchmixes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('NA');
            $table->string('barcode')->default('NA');
            $table->string('code')->default('NA');
            $table->smallInteger('unit_des')->default(0);
            $table->string('inventory_status')->default('NA');
            $table->float('total_weight')->default(0);
            $table->integer('total_weight_id')->default(0);
            $table->float('container_weight')->default(0);
            $table->integer('container_weight_id')->default(0);
            $table->string('liquid_status')->default('NA');
            $table->float('total_volume')->default(0);
            $table->integer('total_volume_id')->default(0);
            $table->float('density')->default(0.0);
            $table->unsignedBigInteger('user_id')->default(1);
            $table->unsignedBigInteger('period_id')->default(1);
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
        Schema::dropIfExists('batchmixes');
    }
}
