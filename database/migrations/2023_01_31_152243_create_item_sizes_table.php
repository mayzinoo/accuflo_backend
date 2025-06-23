<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_sizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->float('countable_unit')->nullable();
            $table->string('countable_size');
            $table->float('empty_weight')->nullable();
            $table->string('empty_weight_size');
            $table->float('full_weight')->nullable();
            $table->string('full_weight_size');
            $table->float('density')->nullable();
            $table->string('density_m_unit')->nullable();
            $table->string('density_v_unit')->nullable();
            $table->string('sizeoption')->nullable();
            $table->string('quantification')->nullable();
            // $table->string('barcode');
            $table->string('package_status');
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
        Schema::dropIfExists('item_sizes');
    }
}
