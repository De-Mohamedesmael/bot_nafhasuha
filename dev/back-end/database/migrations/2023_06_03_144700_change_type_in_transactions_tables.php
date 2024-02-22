<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeInTransactionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            //Withdrawal
            DB::statement("ALTER TABLE transactions MODIFY type ENUM('TopUpCredit','OrderService','JoiningBonus','InvitationBonus','Withdrawal')");


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            DB::statement("ALTER TABLE transactions MODIFY type ENUM('TopUpCredit','OrderService','JoiningBonus','InvitationBonus')");

        });
    }
}
