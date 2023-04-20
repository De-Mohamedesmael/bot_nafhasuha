<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->string('code', 50)->unique();
            $table->integer('is_active')->default(1); // active or now
            $table->double('discount')->default(0);
            $table->enum('type_discount', ['price', 'percentage']);
            $table->double('min_price')->default(0);
            $table->integer('limit')->default(1);
            $table->integer('limit_user')->default(1);
            $table->integer('use')->default(0);
//            $table->foreignid('admin_id')->constrained();
            $table->date('end_date'); // active or now
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
        Schema::dropIfExists('coupons');
    }
}
