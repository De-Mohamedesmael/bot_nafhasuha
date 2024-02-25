<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCyPeriodicTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cy_periodic_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale',5)->index();
            $table->unsignedBigInteger('cy_periodic_id')->unsigned();
            $table->unique(['cy_periodic_id', 'locale']);
            $table->foreign('cy_periodic_id')
                ->references('id')->on('cy_periodics')
                ->onDelete('cascade');
            $table->string('title',150);
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
        Schema::dropIfExists('cy_periodic_translations');
    }
}
