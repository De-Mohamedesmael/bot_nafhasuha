<?php

namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\ApiController;
use App\Http\Resources\MyPackageResource;
use App\Http\Resources\MyWalletTransactionResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\PackageWithFeatureResource;
use App\Models\Package;

use App\Models\PackageUser;
use App\Models\Transaction;
use App\Utils\TransactionUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function App\CPU\translate;

class TransactionController extends ApiController
{
    protected $TransactionUtil;
    protected $count_paginate = 10;
     public function __construct(TransactionUtil $trnUtil)
    {
        $this->TransactionUtil=$trnUtil;
    }

    public function index(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $packages= Package::Active()->orderBy('sort', 'Asc')->get();
        return  responseApi(200, translate('return_data_success'),PackageResource::collection($packages));

    }
    public function myWallet(Request $request)
    {

        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        try {
            $count_paginate=$request->count_paginate?:$this->count_paginate;
            $date['my_wallet']=$this->TransactionUtil->getWalletBalance(auth()->user());
            $date['invite_code']=auth()->user()->invite_code;
            $date['transactions']= MyWalletTransactionResource::collection(Transaction::Active()->where('user_id',auth()->id())->latest()
                ->simplePaginate($count_paginate));
            return  responseApi(200, translate('return_data_success'),$date);
        }catch (\Exception $exception){
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }

    }

     public function show(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $validator = validator($request->all(), [
            'package_id' => 'required|integer|exists:packages,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());



        $package= Package::Active()->with('features')->whereId($request->package_id)->first();
        if(!$package){
            return  responseApiFalse(500, translate('not found'));
        }


        return  responseApi(200, translate('return_data_success'), new PackageWithFeatureResource($package));


    }
    public function StorePackageUser(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));
        $validator = validator($request->all(), [
            'package_id' => 'required|integer|exists:packages,id',
            'is_change' => 'nullable|in:0,1',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        //->where('package_id',$request->package_id)
        if($request->is_change != 1){
            $old_package= PackageUser::where('user_id',auth()->id())->Active()->latest()->first();
            if($old_package){
                return responseApiFalse(500, translate('You_are_already_subscribed_to_a_package'));
            }
        }
        $newDateTime = Carbon::now()->addMonth();
         PackageUser::create([
            'user_id'=>auth()->id(),
            'package_id'=>$request->package_id,
            'start_at'=>date('Y-m-d'),
            'end_at'=>$newDateTime,
        ]);
        return responseApi(200,\App\CPU\translate('Subscription_completed_successfully'));
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
