<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCancellationRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancellation_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id')->unsigned();
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
            $table->unsignedBigInteger('order_service_id')->unsigned()->nullable();
            $table->foreign('order_service_id')->references('id')->on('order_services')->onDelete('cascade');
            $table->unsignedBigInteger('cancel_reason_id')->unsigned();
            $table->foreign('cancel_reason_id')->references('id')->on('cancel_reasons')->onDelete('cascade');
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
        Schema::dropIfExists('cancellation_records');
    }
}
