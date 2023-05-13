<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateVehicleModelsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('vehicle_models', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('vehicle_brand_id')->unsigned()->nullable();
                $table->foreign('vehicle_brand_id')->references('id')->on('vehicle_brands')->onDelete('cascade');
                $table->boolean('status')->default(true);
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
            Schema::dropIfExists('car_models');
        }
    }
