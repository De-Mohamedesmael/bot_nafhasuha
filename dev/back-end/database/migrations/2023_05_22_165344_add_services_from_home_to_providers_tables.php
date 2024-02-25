<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServicesFromHomeToProvidersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->boolean('services_from_home')->default(true)->after('is_active');
            $table->boolean('is_deleted')->default(false)->after('remember_token');
            $table->unsignedBigInteger('deleted_by')->nullable()->after('is_deleted');
            $table->enum('deleted_by_type',['Provider','Admin'])->nullable()->after('deleted_by');
            $table->boolean('is_active')->default(false)->change();
            $table->timestamp('activation_at')->nullable()->after('activation_code');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->boolean('services_from_home');
            $table->boolean('is_deleted');

        });
    }
}
