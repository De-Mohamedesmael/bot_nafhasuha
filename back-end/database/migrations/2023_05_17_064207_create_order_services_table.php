<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('service_id')->nullable();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('vehicle_id')->nullable()->comment('id for user_vehicles');
            $table->foreign('vehicle_id')->references('id')->on('user_vehicles')->onDelete('cascade');


            $table->string('type',100);
            $table->enum('status',['pending', 'approved','received','declined','canceled'])->default('pending');
            $table->enum('type_from',['Home','Center'])->nullable();
            $table->date('date_at')->nullable();
            $table->string('time_at',10)->nullable();
            $table->string('address',300)->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();

            $table->text('details')->nullable();
            $table->text('reason')->nullable();

            $table->enum('canceled_type',['Admin','User','Provider'])->nullable();
            $table->unsignedBigInteger('canceled_by')->nullable();
            $table->unsignedBigInteger('update_by')->nullable();
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
        Schema::dropIfExists('order_services');
    }
}
