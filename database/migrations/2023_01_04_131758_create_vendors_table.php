<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('NA');
            $table->string('code')->default('NA');
            $table->integer('invoice_due_date')->default(0);
            $table->integer('invoice_due_date_unit')->default('0');
            $table->string('status')->default('NA');
            $table->string('address_line_1')->default('');
            $table->string('address_line_2')->default('');
            $table->string('city')->default('');
            $table->string('state')->default('');
            $table->integer('country_code')->default(0);
            $table->integer('postal_code')->default(0);
            $table->string('phone')->default('');
            $table->string('cell')->default('');
            $table->string('fax')->default('');
            $table->string('email')->default('');
            $table->string('notes')->default('');
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
        Schema::dropIfExists('vendors');
    }
}
