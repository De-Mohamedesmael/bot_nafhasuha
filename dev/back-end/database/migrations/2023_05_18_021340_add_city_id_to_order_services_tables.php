<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCityIdToOrderServicesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_services', function (Blueprint $table) {
            //'city_id' => 'required|integer|exists:cities,id',
            //            'position' => 'required|string|in:Left,Right,Front,Behind',

            $table->unsignedBigInteger('city_id')->nullable()->after('transaction_id');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');

            $table->enum('position',['Left','Right','Front','Behind'])
                ->nullable()
                ->after('type_from');

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
            $table->unsignedBigInteger('city_id');
            $table->enum('position',['Left','Right','Front','Behind']);
        });
    }
}
