<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('vehicle_type_id')->unsigned()->nullable();
            $table->foreign('vehicle_type_id')->references('id')->on('vehicle_types')->onDelete('cascade');
            $table->unsignedBigInteger('vehicle_model_id')->unsigned();
            $table->foreign('vehicle_model_id')->references('id')->on('vehicle_models')->onDelete('cascade');
            $table->unsignedBigInteger('vehicle_manufacture_year_id')->unsigned()->nullable();
            $table->foreign('vehicle_manufacture_year_id')->references('id')->on('vehicle_manufacture_years')->onDelete('cascade');
            $table->unsignedBigInteger('vehicle_id')->unsigned()->nullable();
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->string('title',200)->nullable();
            $table->string('letters_ar',10);
            $table->string('numbers_ar',10);
            $table->string('letters_en',10);
            $table->string('numbers_en',10);
            $table->integer('periodic_inspection')->nullable();
            $table->boolean('status')->default(true);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('user_vehicles');
    }
}
