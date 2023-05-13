<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateVehicleBrandsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('vehicle_brands', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('vehicle_type_id')->unsigned()->nullable();
                $table->foreign('vehicle_type_id')->references('id')->on('vehicle_types')->onDelete('cascade');
                $table->boolean('status')->default(true);
                $table->string('image',250)->nullable();

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
            Schema::dropIfExists('cars');
        }
    }
