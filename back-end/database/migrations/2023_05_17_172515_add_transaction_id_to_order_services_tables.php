<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionIdToOrderServicesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_services', function (Blueprint $table) {
            $table->unsignedBigInteger('transaction_id')->nullable()->after('vehicle_id');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_services', function (Blueprint $table) {
            //
        });
    }
}
