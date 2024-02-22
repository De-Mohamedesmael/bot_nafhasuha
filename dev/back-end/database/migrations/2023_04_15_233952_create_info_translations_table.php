<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfoTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale',5)->index();
            $table->unsignedBigInteger('info_id')->unsigned();
            $table->unique(['info_id', 'locale']);

            $table->foreign('info_id')->references('id')->on('infos')->onDelete('cascade');


            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('meta_description',350);
            $table->string('meta_keywords',350);

            $table->string('video',250)->nullable();
            $table->string('img',250)->nullable();
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
        Schema::dropIfExists('info_translations');
    }
}
