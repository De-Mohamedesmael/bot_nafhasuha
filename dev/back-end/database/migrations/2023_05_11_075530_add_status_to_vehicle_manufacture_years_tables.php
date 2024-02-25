<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToVehicleManufactureYearsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_manufacture_years', function (Blueprint $table) {
            $table->boolean('status')->default(true)->after('title');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_manufacture_years', function (Blueprint $table) {
            $table->boolean('status');

        });
    }
}
