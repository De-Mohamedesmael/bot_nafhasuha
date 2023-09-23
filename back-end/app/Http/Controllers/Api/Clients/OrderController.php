<?php

namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\ApiController;
use App\Http\Resources\OrderServiceAllDataResource;
use App\Http\Resources\OrderServiceResource;
use App\Http\Resources\PriceQuotesResource;
use App\Models\OrderService;
use App\Models\PriceQuote;
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
        $orders= auth()->user()->orders()->NotCompleted()->latest();


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
        $orders= auth()->user()->orders()->Completed()->latest();

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
        $orders= auth()->user()->orders()->Canceld()->latest();

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


        $PriceQuote= PriceQuote::find($request->price_id);

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


    public function acceptQuotes(Request $request)
    {
        $validator = validator($request->all(), [
            'price_id' => 'required|integer',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());


        $PriceQuote= PriceQuote::find($request->price_id);

        if(!$PriceQuote){
            return responseApi(404, translate("Price Quote Not Found"));
        }

        if($PriceQuote->status == "Reject"){
            return responseApi(405, translate("Price quote cannot be accepted as it has been declined"));
        }elseif($PriceQuote->status =='Accept'){
            return responseApi(405, translate("This price quote has been accepted successfully"));
        }

        $order= auth()->user()->orders()->whereId($PriceQuote->order_service_id)
            ->where('status','pending')->first();

        if(!$order)
            return responseApi(404, translate("Order Not Found"));
        DB::beginTransaction();
        try {
//            $PriceQuote->status='Accept';
//            $PriceQuote->save();

            DB::commit();
            return  responseApi(200, translate('Price quote has been successfully accepted'));
        }catch (\Exception $exception){
            DB::rollBack();
//            dd($exception);
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }
}
