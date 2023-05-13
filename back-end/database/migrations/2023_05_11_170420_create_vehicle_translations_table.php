<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_brand_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale',5)->index();
            $table->unsignedBigInteger('vehicle_brand_id')->unsigned();
            $table->unique(['vehicle_brand_id', 'locale']);
            $table->foreign('vehicle_brand_id')->references('id')->on('vehicle_brands')->onDelete('cascade');
            $table->string('title',200);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_translations');
    }
}
