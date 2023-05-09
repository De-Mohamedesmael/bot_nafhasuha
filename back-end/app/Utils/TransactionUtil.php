<?php

namespace App\Utils;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionUtil
{
    /**
     * Get Wallet Balance For User
     *
     * @param User $user
     * @return float
     */
    public function getWalletBalance(User $user): float
    {
        $amountCredit = Transaction::Active()
            ->whereIn('type', ['TopUpCredit', 'JoiningBonus', 'InvitationBonus'])
            ->where('user_id', $user->id)
            ->sum('final_total');

        $amountDebit = Transaction::Active()
            ->whereIn('type', ['OrderService'])
            ->where('user_id', $user->id)
            ->sum('final_total');

        return $amountCredit - $amountDebit;
    }



}
