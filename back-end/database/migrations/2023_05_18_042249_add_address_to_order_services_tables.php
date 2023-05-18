<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressToOrderServicesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_services', function (Blueprint $table) {
            $table->string('address_to',300)->nullable()->after('lat');
            $table->string('lat_to')->nullable()->after('address_to');
            $table->string('long_to')->nullable()->after('lat_to');
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
            $table->string('address_to',300);
            $table->string('lat_to')->nullable();
            $table->string('long_to')->nullable();
        });
    }
}
