<?php

namespace App\Http\Controllers\Api\Providers;

use App\Http\Controllers\ApiController;
use App\Http\Resources\MyPackageResource;
use App\Http\Resources\Providers\MyWalletTransactionResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\PackageWithFeatureResource;
use App\Models\Package;

use App\Models\PackageUser;
use App\Models\Provider;
use App\Models\Transaction;
use App\Utils\TransactionUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function App\CPU\translate;

class TransactionController extends ApiController
{
    protected $TransactionUtil;
    protected $count_paginate = 10;
     public function __construct(TransactionUtil $trnUtil)
    {
        Config::set( 'jwt.user', 'App\Models\Provider' );
        Config::set( 'auth.providers.users.model', Provider::class );
        $this->TransactionUtil=$trnUtil;

    }


    public function myWallet(Request $request)
    {

        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        try {
            $count_paginate=$request->count_paginate?:$this->count_paginate;
            $date['my_wallet']=$this->TransactionUtil->getWalletProviderBalance(auth()->user());

            $credit=$this->TransactionUtil->getProviderTransactionCredit(auth()->id(),$count_paginate);
            $debit=$this->TransactionUtil->getProviderTransactionDebit(auth()->id(),$count_paginate);

            $date['credit']= MyWalletTransactionResource::collection($credit);
            $date['debit']= MyWalletTransactionResource::collection($debit);
            return  responseApi(200, translate('return_data_success'),$date);
        }catch (\Exception $exception){
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }

    }


    public function StoreWithdrawalRequest(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));


        $validator = validator($request->all(), [
            'bank_id' => 'required|integer|exists:banks,id',
            'full_name' => 'required|string|max:100',
            'iban' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $my_wallet=$this->TransactionUtil->getWalletProviderBalance(auth()->user());


        if($my_wallet < $request->amount){
            return responseApiFalse(405, __('messages.Sorry_the_current_balance_is',['amount'=>$my_wallet]));

        }

        DB::beginTransaction();
        try {
            $this->TransactionUtil->saveProviderWithdrawalRequest(auth()->user(),$request->bank_id,$request->full_name,$request->amount);
            DB::commit();
            return responseApi(200,\App\CPU\translate('Request_has_been_successfully'));
        }catch (\Exception $exception){
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }


    }
    public function RechargeMyWallet(Request $request)
    {
        $validator = validator($request->all(), [
            'amount' => 'required',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        try {
            DB::beginTransaction();
            $date_at=null;
            $date=$this->TransactionUtil->addWalletBalanceProvider(auth()->id(),$request->amount,'Provider',auth()->id(),$date_at);
            DB::commit();
            return  responseApi(200, translate('return_data_success'),$date);
        }catch (\Exception $exception){
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }

    }
    public function MyPackage(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));
            $old_package= PackageUser::where('user_id',auth()->id())->Active()->latest()->with('package')->first();
            if(!$old_package){
                return responseApiFalse(500, translate('You_are_not_subscribed_to_any_package'));
            }

        return responseApi(200,\App\CPU\translate('return_data_success') , new MyPackageResource($old_package));
    }
}
