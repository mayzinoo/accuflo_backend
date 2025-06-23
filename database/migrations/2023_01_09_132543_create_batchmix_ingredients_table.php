<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchmixIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batchmix_ingredients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batchmix_id');
            //$table->foreign('batchmix_id')->references('id')->on('batchmixes');

            $table->unsignedBigInteger('item_id');
            //$table->foreign('item_id')->references('id')->on('items');
            $table->string('item_name')->default('NA');
            $table->integer('qty')->default(0);
            $table->string('uom')->default('NA');
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
        Schema::dropIfExists('batchmix_ingredients');
    }
}
