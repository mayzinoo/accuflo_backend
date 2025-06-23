<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id');
            $table->unsignedBigInteger('period_id');
            $table->unsignedBigInteger('user_id');
            $table->string('invoice_number');
            $table->date('invoice_delivery_date');
            $table->date('invoice_due_date');
            $table->string('total_quantity')->nullable();
            $table->float('total_taxes')->default(0.0);
            $table->float('total_deposits')->default(0.0);
            $table->float('total_delivery')->default(0.0);
            $table->float('total_non_inventory')->default(0.0);
            $table->float('total_misc')->default(0.0);
            $table->float('total_cost_excluding_taxes')->default(0.0);
            $table->float('total_cost')->default(0.0);
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
        Schema::dropIfExists('invoices');
    }
}
