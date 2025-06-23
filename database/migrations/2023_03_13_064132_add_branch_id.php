<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('branch_id')->after('company_id')->nullable();
        });
        Schema::table('batchmixes', function (Blueprint $table) {
            $table->integer('branch_id')->after('user_id')->nullable();
        });
        Schema::table('full_counts', function (Blueprint $table) {
            $table->integer('branch_id')->after('user_id')->nullable();
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('branch_id')->after('user_id')->nullable();
        });
        Schema::table('items', function (Blueprint $table) {
            $table->integer('branch_id')->after('user_id')->nullable();
        });
        Schema::table('periods', function (Blueprint $table) {
            $table->integer('branch_id')->after('user_id')->nullable();
        });
        Schema::table('recipes', function (Blueprint $table) {
            $table->integer('branch_id')->after('user_id')->nullable();
        });
        Schema::table('weights', function (Blueprint $table) {
            $table->integer('branch_id')->after('user_id')->nullable();
        });
        Schema::table('stations', function (Blueprint $table) {
            $table->integer('branch_id')->after('user_id')->nullable();
        });
        Schema::table('sessions', function (Blueprint $table) {
            $table->integer('branch_id')->after('user_id')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        Schema::table('batchmixes', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        Schema::table('full_counts', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        Schema::table('periods', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        Schema::table('weights', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        Schema::table('stations', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
    }
}
