<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFcmTokenProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fcm_token_providers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id')->unsigned()->nullable();
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
            $table->string('token',350);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fcm_token_providers');
    }
}
