<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderServiceIdToProviderRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('provider_rates', function (Blueprint $table) {
            $table->unsignedBigInteger('order_service_id')->after('user_id')->unsigned()->nullable();
            $table->foreign('order_service_id')->references('id')->on('order_services')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provider_rates', function (Blueprint $table) {
            //
        });
    }
}
