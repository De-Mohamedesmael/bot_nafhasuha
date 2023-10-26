<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnFromOrderServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_services', function (Blueprint $table) {
            $table->dropColumn('reason');
            $table->unsignedBigInteger('cancel_reason_id')->unsigned()
                ->nullable()->after('details');
            $table->foreign('cancel_reason_id')->references('id')->on('cancel_reasons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_services', function (Blueprint $table) {
            //
        });
    }
}
