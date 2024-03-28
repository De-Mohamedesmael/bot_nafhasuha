<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCyPeriodicProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cy_periodic_providers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id')->unsigned();
            $table->unsignedBigInteger('cy_periodic_id')->unsigned();
            $table->foreign('cy_periodic_id')
                ->references('id')->on('cy_periodics')
                ->onDelete('cascade');
            $table->foreign('provider_id')
                ->references('id')->on('providers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cy_periodic_providers');
    }
}
