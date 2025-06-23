<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('recipe_id');
            //$table->foreign('recipe_id')->references('id')->on('recipes');

            //$table->unsignedBigInteger('period_id')->default(1);
            //$table->foreign('period_id')->references('id')->on('periods');

            $table->string('item_id');
            $table->integer('qty')->default(0);
            $table->string('package_id');
            $table->string('uom_text')->nullable();
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
        Schema::dropIfExists('recipe_ingredients');
    }
}
