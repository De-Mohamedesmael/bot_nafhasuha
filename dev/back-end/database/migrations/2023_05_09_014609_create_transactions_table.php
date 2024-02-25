<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('service_id')->nullable();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');

            $table->string('invoice_no',50)->nullable();
            $table->enum('type',['TopUpCredit','OrderService','JoiningBonus','InvitationBonus']);
            $table->unsignedBigInteger('type_id')->nullable();
            $table->enum('status',['pending', 'approved','received','declined','canceled'])->default('pending');


            $table->string('discount_type')->nullable();
            $table->decimal('discount_value', 15, 4)->default(0)->comment('discount value applied by user');
            $table->decimal('discount_amount', 15, 4)->default(0)->comment('amount calculated based on type and value');
            $table->decimal('grand_total', 15, 4)->nullable()->comment('amount only');
            $table->decimal('final_total', 15, 4)->default(0.0000)->comment('amount + discount amount');
            $table->text('details')->nullable();
            $table->text('reason')->nullable();

            $table->enum('canceled_type',['Admin','User','Provider'])->nullable();
            $table->unsignedBigInteger('canceled_by')->nullable();

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
        Schema::dropIfExists('transactions');
    }
}
