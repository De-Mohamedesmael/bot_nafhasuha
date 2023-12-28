<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_service_id')->unsigned();
            $table->foreign('order_service_id')->references('id')->on('order_services')->onDelete('cascade');
            $table->enum('status',['Pending','Accept','Reject'])->default('Pending');

            $table->decimal('price');
            $table->date('date_at')->nullable();
            $table->string('time_at',10)->nullable();
            $table->text('details')->nullable();
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
        Schema::dropIfExists('maintenance_reports');
    }
}
