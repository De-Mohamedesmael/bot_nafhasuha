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

    /**
     * Save Invitation Bonus  in Transaction Table for user
     *
     * @param User $user
     * @param string $code
     * @return boolean
     */
    public function SaveInviteCode($user,$code)
    {
        $invite_by = User::where('invite_code',$code)->first();
        if($invite_by){
            $user->invite_by = $invite_by->id;
            $user->save();
            $amountInvitationBonus=\Settings::get('InvitationBonusValue');
            if($amountInvitationBonus){
                $Transaction= Transaction::create([
                    'user_id'=>$invite_by->id,
                    'type'=>'InvitationBonus',
                    'type_id'=>$user->id,
                    'status'=>'pending',
                    'grand_total'=>$amountInvitationBonus,
                    'final_total'=>$amountInvitationBonus,
                ]);
                $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                $Transaction->invoice_no='IB'.$randomNumber.'-'.$invite_by->id.'u'.$Transaction->id;
                $Transaction->save();
            }

        }


        return true;
    }
    /**
     * Save Joining Bonus  in Transaction Table for user
     *
     * @param User $user
     * @return boolean
     */
    public function SaveJoiningBonus($user)
    {

            $amountJoiningBonusValue=\Settings::get('JoiningBonusValue');
            if($amountJoiningBonusValue){
                $Transaction= Transaction::create([
                    'user_id'=>$user->id,
                    'type'=>'JoiningBonus',
                    'status'=>'received',
                    'grand_total'=>$amountJoiningBonusValue,
                    'final_total'=>$amountJoiningBonusValue,
                ]);
                $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                $Transaction->invoice_no='IB'.$randomNumber.'-'.$user->id.'u'.$Transaction->id;
                $Transaction->save();
            }
        return true;
    }
    /**
     * Active Invitation Bonus  in Transaction Table for user
     *
     * @param int $invite_by
     * @param int $user_id
     * @return boolean
     */
    public function ActiveInvitationBonus($invite_by,$user_id)
    {

        $Transaction = Transaction::where('user_id',$invite_by)->where('type','InvitationBonus')
            ->where('status','pending')->where('type_id',$user_id)
            ->first();
        if($Transaction){
            $Transaction->status = 'received';
            $Transaction->save();
        }


        return true;
    }




    /**
     * store Transaction  for Order Service
     *
     * @param object $OrderService
     * @param array $discount
     * @param integer $type_id
     * @return object
     */
    public function saveTransactionForOrderService($OrderService,$discount,$type_id=null):object
    {



        $Transaction= Transaction::create([
            'user_id'=>$OrderService->user_id,
            'service_id'=>$OrderService->service_id,
            'type'=>'OrderService',
            'status'=>'pending',
            'type_id'=>$type_id,
            'discount_type'=>$discount['discount_type'],
            'discount_value'=>$discount['discount_value'],
            'discount_amount'=>$discount['discount_value'],
            'grand_total'=>0,
            'final_total'=>0,
        ]);

        $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $Transaction->invoice_no='OS'.$randomNumber.'-'.$OrderService->user_id.'u'.$Transaction->id;
        $Transaction->save();


        return $Transaction;
    }




}
