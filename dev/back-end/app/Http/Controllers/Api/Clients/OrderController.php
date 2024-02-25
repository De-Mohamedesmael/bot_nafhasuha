<?php

namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\ApiController;
use App\Http\Resources\MaintenanceReportResource;
use App\Http\Resources\OrderServiceAllDataResource;
use App\Http\Resources\OrderServiceResource;
use App\Http\Resources\PriceQuotesResource;
use App\Models\MaintenanceReport;
use App\Models\OrderService;
use App\Models\PriceRequest;
use App\Models\FcmTokenProvider;

use App\Utils\ServiceUtil;
use App\Utils\TransactionUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function App\CPU\translate;

class OrderController extends ApiController
{
    protected $ServiceUtil,$TransactionUtil, $count_paginate = 10;

    public function __construct(ServiceUtil $ServUtil,TransactionUtil $trnUtil)
    {
        $this->ServiceUtil=$ServUtil;
        $this->TransactionUtil=$trnUtil;

    }

    public function indexPending(Request $request)
    {
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $orders= auth()->user()->orders()->withcount(['price_requests'=>function ($q){
                $q->whereNull('status');
            }])->where(function ($pq){
            $pq->whereNull('parent_id')->NotCompleted();
        })->orwhere(function ($pq){
            $pq->wherein('status',  ['completed','canceled'])->wherehas('children',function ($q){
                $q->whereIn('status', ['pending', 'approved', 'PickUp', 'received']);
            });
        })->latest();


        if($count_paginate == 'ALL'){
            $orders=  $orders->get();
        }else{
            $orders=  $orders->simplePaginate($count_paginate);
        }
        return  responseApi(200, translate('return_data_success'),OrderServiceResource::collection($orders));

    }


    public function indexCompleted(Request $request)
    {
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $orders = auth()->user()->orders()
            ->whereNull('parent_id')
            ->whereDoesntHave('children', function($q) {
                $q->whereIn('status', ['pending', 'approved', 'PickUp', 'received']);
            })
            ->Completed()
            ->latest();

        if($count_paginate == 'ALL'){
            $orders=  $orders->get();
        }else{
            $orders=  $orders->simplePaginate($count_paginate);
        }
        
        
        
        return  responseApi(200, translate('return_data_success'),OrderServiceResource::collection($orders));

    }


    public function indexCanceld(Request $request)
    {
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $orders= auth()->user()->orders()->whereNull('parent_id')->Canceld()->latest();

        if($count_paginate == 'ALL'){
            $orders=  $orders->get();
        }else{
            $orders=  $orders->simplePaginate($count_paginate);
        }
        return  responseApi(200, translate('return_data_success'),OrderServiceResource::collection($orders));

    }
    public function show($id)
    {
        $order= auth()->user()->orders()->whereId($id)->first();
        if(!$order)
            return responseApi(404, translate("Order Not Found"));


        return  responseApi(200, translate('return_data_success'),new OrderServiceAllDataResource($order));

    }
    public function GetByInvoiceNo(Request $request)
    {
        $invoice_no=$request->invoice_no;
//        $order= auth()->user()->orders()->wherehas('transaction',function ($q) use ($invoice_no){
//            $q->where('invoice_no',$invoice_no);
//        })->first();
        $order= OrderService::wherehas('transaction',function ($q) use ($invoice_no){
            $q->where('invoice_no',$invoice_no);
        })->first();
        if(!$order)
            return responseApi(404, translate("Order Not Found"));


        return  responseApi(200, translate('return_data_success'),new OrderServiceAllDataResource($order));

    }

    public function quotes($id)
    {
        $count_paginate=request()->count_paginate?:$this->count_paginate;

        $order= auth()->user()->orders()->whereId($id)->where('status','pending')->first();
        if(!$order)
            return responseApi(404, translate("Order Not Found"));

        $PriceQuotes =$this->ServiceUtil->getPriceQuotesForOrder($order,$count_paginate);

        $PriceQuotes=  PriceQuotesResource::collection($PriceQuotes);

        return  responseApi(200, translate('return_data_success'),$PriceQuotes);

    }
    public function rejectQuotes(Request $request)
    {
        $validator = validator($request->all(), [
            'price_id' => 'required|integer',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        $PriceQuote= PriceRequest::find($request->price_id);

        if(!$PriceQuote){
            return responseApi(404, translate("Price Quote Not Found"));
        }

        if($PriceQuote->status == "Reject"){
            return responseApi(405, translate("This quote has been declined by the recipient"));
        }elseif($PriceQuote->status =='Accept'){
            return responseApi(405, translate("This price quote has been accepted successfully"));
        }

        $order= auth()->user()->orders()->whereId($PriceQuote->order_service_id)
            ->where('status','pending')->first();

        if(!$order)
            return responseApi(404, translate("Order Not Found"));

        $PriceQuote->status='Reject';
        $PriceQuote->save();


        return  responseApi(200, translate('Price quote has been successfully declined'));

    }

    public function acceptMaintenanceReport(Request $request)
    {
        $validator = validator($request->all(), [
            'maintenance_report_id' => 'required|integer',
            'payment_method' => 'required|string|in:Online,Wallet',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        $MaintenanceReport= MaintenanceReport::find($request->maintenance_report_id);

        if(!$MaintenanceReport){
            return responseApi(404, translate("Maintenance Report Not Found"));
        }

        if($MaintenanceReport->status == "Reject"){
            return responseApi(405, translate("Maintenance Report cannot be accepted as it has been declined"));
        }elseif($MaintenanceReport->status =='Accept'){
            return responseApi(405, translate("This Maintenance Report has been accepted successfully"));
        }

        $order= auth()->user()->orders()->whereId($MaintenanceReport->order_service_id)
            ->first();

        if(!$order)
            return responseApi(404, translate("Order Not Found"));


        DB::beginTransaction();
        try {
            $trans=$order->transaction;
            $discount['discount_value'] = 0;
            $discount['discount_type'] = null;

            $grand_total=$MaintenanceReport->price ;
            $final_total=$grand_total-$discount['discount_value'];

            if($request->payment_method == 'Wallet'){
                $wallet_user=$this->TransactionUtil->getWalletBalance(auth()->user());
                $price_=$final_total;
                if($wallet_user < $price_){
                    DB::rollBack();
                    return responseApiFalse(405, translate('Your wallet balance is insufficient'));
                }
            }
            $order->payment_method=$request->payment_method;
            $order->save();
            $trans->discount_type=$discount['discount_type'];
            $trans->discount_value=$discount['discount_value'];
            $trans->discount_amount=$discount['discount_value'];
            $trans->grand_total=$grand_total;
            $trans->final_total=$final_total;
            $trans->deducted_total=($final_total  * \Settings::get('percent_'.$order->type,10)) / 100;;
            $trans->save();
            $MaintenanceReport->status='Accept';
            $MaintenanceReport->save();
            $provider=$order->provider;
            if($provider->is_notification){
                $code=$order->transaction?->invoice_no;
               $notarray = [
                    'type' => 1,
                    'order_step' => 'Accept',
                    'image' => null
                ];
                $type_model = 'Provider';
                $notarray['ar']['title'] = __('notifications.order_step_MaintenanceReport2.title', [], 'ar');
                $notarray['en']['title'] = __('notifications.order_step_MaintenanceReport2.title', [], 'en');
                $notarray['ar']['body'] = __('notifications.order_step_MaintenanceReport2.body', ['code' => $code], 'ar');
                $notarray['en']['body'] = __('notifications.order_step_MaintenanceReport2.body', ['code' => $code], 'en');
                $this->pushNotofarray($notarray, [$provider->id], $order->id, $type_model);

            }
            DB::commit();

            return  responseApi(200, translate('Maintenance Report has been successfully accepted'),$order->id);
        }catch (\Exception $exception){
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }

    public function rejectMaintenanceReport(Request $request)
    {
        $validator = validator($request->all(), [
            'order_id' => 'required|integer|exists:order_services,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        $MaintenanceReport= MaintenanceReport::where('order_service_id',$request->order_id)
        ->first();

        $order= auth()->user()->orders()->whereId($request->order_id)
            ->first();

        if(!$order)
            return responseApi(404, translate("Order Not Found"));


        DB::beginTransaction();
        try {
            if($MaintenanceReport){
                if($MaintenanceReport->status == "Reject"){
                    return responseApi(405, translate("Maintenance Report cannot be accepted as it has been declined"));
                }elseif($MaintenanceReport->status =='Accept'){
                    return responseApi(405, translate("This Maintenance Report has been accepted successfully"));
                }
                $MaintenanceReport->status='Reject';
                $MaintenanceReport->save();
            }
            $this->ServiceUtil->CanceledOrderService($request->order_id,null,'User',auth()->id());

            $OrderService =  $this->ServiceUtil->SetOrderToTransportVehicle($order,$order->provider,'canceled');
            
            $this->pushNotof('Order',$OrderService,$order->user_id,1);

            DB::commit();
            return  responseApi(200, translate('The request has been successfully cancelled'),$request->order_id);
        }catch (\Exception $exception){
            
            dd($exception);
            DB::rollBack();
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }
    public function getMaintenanceReport(Request $request)
    {
        $validator = validator($request->all(), [
            'order_id' => 'required|integer',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());



        try {

            $MaintenanceReport= MaintenanceReport::where('order_service_id',$request->order_id)->first();

            if(!$MaintenanceReport){
                return responseApi(404, translate("Maintenance Report Not Found"));
            }
            $MaintenanceReport =new MaintenanceReportResource($MaintenanceReport);
            return  responseApi(200, translate('return_data_success'),$MaintenanceReport);
        }catch (\Exception $exception){
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }

    public function priceRequestsCountForPending(Request $request)
    {
        $validator = validator($request->all(), [
            'order_id' => 'required|integer|exists:order_services,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $requests= PriceRequest::where('order_service_id',$request->order_id)->get();


        return  responseApi(200, translate('return_data_success'),PriceQuotesResource::collection($requests));

    }



}
