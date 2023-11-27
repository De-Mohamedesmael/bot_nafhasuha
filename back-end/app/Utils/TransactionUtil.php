<?php

namespace App\Utils;

use App\Http\Resources\OrderServiceResource;
use App\Models\CyPeriodic;
use App\Models\OrderService;
use App\Models\Provider;
use App\Models\Service;
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
     * Get Wallet Balance For User
     *
     * @param integer $user_id
     * @param float $amount
     * @param string $add_type
     * @param integer $add_by
     * @param string $date_at
     * @return boolean
     */
    public function addWalletBalanceCustomer($user_id,$amount,$add_type,$add_by,$date_at)
    {

        $Transaction= Transaction::create([
            'user_id'=>$user_id,
            'type'=>'TopUpCredit',
            'status'=>'received',
            'grand_total'=>$amount,
            'final_total'=>$amount,
            'completed_at'=>$date_at,
            'created_by_type'=>$add_type,
            'created_by'=>$add_by,
        ]);
        $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $Transaction->invoice_no='TP'.$randomNumber.'-'.$user_id.'u'.$Transaction->id;
        $Transaction->save();

        return  true;
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
    public function saveTransactionForOrderService($OrderService,$discount,$type_id=null,$suggested_price=0):object
    {

        $grand_total=0;
        $final_total=0;
        if($OrderService->type == 'PeriodicInspection'){
            $cy_periodic = CyPeriodic::whereId($OrderService->cy_periodic_id)->first();
            $grand_total=$cy_periodic->price;
            $final_total=$cy_periodic->price - $discount['discount_value'];
        }


        $Transaction= Transaction::create([
            'user_id'=>$OrderService->user_id,
            'service_id'=>$OrderService->service_id,
            'type'=>'OrderService',
            'status'=>'pending',
            'type_id'=>$type_id,
            'suggested_price'=>$suggested_price,
            'discount_type'=>$discount['discount_type'],
            'discount_value'=>$discount['discount_value'],
            'discount_amount'=>$discount['discount_value'],
            'grand_total'=>$grand_total,
            'final_total'=>$final_total,
        ]);

        $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $Transaction->invoice_no='OS'.$randomNumber.'-'.$OrderService->user_id.'u'.$Transaction->id;
        $Transaction->save();


        return $Transaction;
    }




    /**
     * Get Wallet Balance For Provider
     *
     * @param Provider $provider
     * @return float
     */
    public function getWalletProviderBalance(Provider $provider): float
    {


        $amountCredit = Transaction::Active()
            ->whereIn('type', ['OrderService','TopUpCredit'])
            ->where('provider_id', $provider->id)
            ->sum('final_total');

        $amountDebit = Transaction::Active()
            ->whereIn('type', ['Withdrawal'])
            ->where('provider_id', $provider->id)
            ->sum('final_total');
        return $amountCredit - $amountDebit;
    }



    /**
     * Get Provider Transactions  to Credit
     *
     * @param integer $provider_id
     * @param integer $count_paginate
     * @return object
     */
    public function getProviderTransactionCredit($provider_id,$count_paginate=null): object
    {

        $transactions=Transaction::Active()->where('provider_id',$provider_id)
            ->whereIn('type', ['OrderService','TopUpCredit'])
            ->latest();

        if($count_paginate){
            $transactions=  $transactions ->simplePaginate($count_paginate);
        }else{
            $transactions=$transactions->get();
        }


        return $transactions;
    }
    /**
     * Get Provider Transactions  to Debit
     *
     * @param integer $provider_id
     * @param integer $count_paginate
     * @return object
     */
    public function getProviderTransactionDebit($provider_id,$count_paginate=null): object
    {

        $transactions=Transaction::Active()->where('provider_id',$provider_id)
            ->whereIn('type', ['Withdrawal'])
            ->latest();

        if($count_paginate){
            $transactions=  $transactions ->simplePaginate($count_paginate);
        }else{
            $transactions=$transactions->get();
        }


        return $transactions;
    }

    /**
     * save Transactions  Withdrawal Request  For Provider
     *
     * @param Provider $provider
     * @param integer $bank_id
     * @param string $fullName
     * @param float $amount
     * @return object
     */
    public function saveProviderWithdrawalRequest($provider,$bank_id,$fullName,$amount): object
    {
        $data=[
            'provider_id'=>$provider->id,
            'type'=>'Withdrawal',
            'type_id'=>$bank_id,
            'status'=>'pending',
            'grand_total'=>$amount,
            'final_total'=>$amount,
            'full_name'=>$fullName,
        ];
        $transaction=Transaction::create($data);

        $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $transaction->invoice_no='WD'.$randomNumber.'-'.$provider->id.'v'.$transaction->id;
        $transaction->save();
        return $transaction;
    }

    /**
     * save OrderService  Request  For Provider By categories ids
     *
     * @param array $categories_ids
     * @param object $provider
     * @return object
     */
    public function getProviderPendingOrderServiceByCategories($categories_ids,$provider): object
    {
        $services_ids = Service::wherehas('categories',function ($q) use ($categories_ids){
             $q->wherein('categories.id',$categories_ids);
         })->pluck('id');
        $sqlDistance = DB::raw('( 111.045 * acos( cos( radians(' .$provider->lat . ') )
       * cos( radians( `lat` ) )
       * cos( radians( `long` )
       - radians(' . $provider->long  . ') )
       + sin( radians(' . $provider->lat  . ') )
       * sin( radians( `lat` ) ) ) )');
        $orders=OrderService::where('status','pending')
            ->wherein('service_id',$services_ids)->where(function ($q) use ($categories_ids){
            $q->wherein('category_id',$categories_ids)
                ->orwhereNull('category_id');
        })->selectRaw("*,{$sqlDistance} as distance")->get();
        return $orders;
    }



}
