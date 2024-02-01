<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\Provider;
use App\Models\Slider;
use App\Models\OrderService;
use App\Models\System;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserRequest;
use App\Utils\TransactionUtil;
use App\Utils\ServiceUtil;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use function App\CPU\translate;

class OrderController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;
    protected $transactionUtil;
    protected $serviceUtil;


    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @param TransactionUtil $transactionUtil
     * @param ServiceUtil $serviceUtil
     * @return void
     */
    public function __construct(Util $commonUtil, TransactionUtil $transactionUtil,ServiceUtil $serviceUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->transactionUtil = $transactionUtil;
        $this->serviceUtil = $serviceUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($status=null)
    {
        if (request()->ajax()) {

            $query = OrderService::leftJoin('transactions', 'transactions.id', '=', 'order_services.transaction_id')
                ->leftJoin('users', 'users.id', '=', 'order_services.user_id')
                ->leftJoin('providers', 'providers.id', '=', 'order_services.provider_id')
                ->select('order_services.*',
                'transactions.invoice_no',
                'transactions.invoice_no',
                'transactions.final_total',
                'transactions.grand_total',
                'transactions.discount_amount',
                'transactions.suggested_price',
                'users.name as client_name',
                'users.phone as client_phone',
                'providers.name as provider_name',
                'providers.phone as provider_phone',
            );
            ///'pending','approved','PickUp','received','completed','declined','canceled'
            if($status){
                $array_status=[];
                switch ($status){
                    case 'pending':
                        $array_status=['pending'];
                        break;
                    case 'approved':
                        $array_status=['approved','PickUp'];
                        break;
                    case 'completed':
                        $array_status=['received','completed'];
                        break;
                    case 'canceled':
                        $array_status=['declined','canceled'];
                        break;
                }

                $query= $query->wherein('order_services.status',$array_status);
            }


            if (!empty(request()->category_id)) {
                $query->where('order_services.category_id', request()->category_id);
            }
            if (!empty(request()->city_id)) {
                $query->where('order_services.city_id', request()->city_id);
            }
            if (!empty(request()->area_id)) {
                $query->where('order_services.area_id', request()->area_id);
            }

            if (!empty(request()->user_id)) {
                $query->where('order_services.user_id', request()->user_id);
            }
            if (!empty(request()->payment_method)) {
                $query->where('order_services.payment_method', request()->payment_method);
            }

            if (!empty(request()->category_id)) {
                $query->where('order_services.category_id', request()->category_id);
            }
            if (!empty(request()->start_date)) {
                $query->whereDate('order_services.created_at', '>=', request()->start_date);
            }
            if (!empty(request()->end_date)) {
                $query->whereDate('order_services.created_at', '<=', request()->end_date);
            }
            if (!empty(request()->start_time)) {
                $query->where('order_services.created_at', '>=', request()->start_date . ' ' . Carbon::parse(request()->start_time)->format('H:i:s'));
            }
            if (!empty(request()->end_time)) {
                $query->where('order_services.created_at', '<=', request()->end_date . ' ' . Carbon::parse(request()->end_time)->format('H:i:s'));
            }
            if (!empty(request()->complete_start_date)) {
                $query->whereDate('order_services.completed_at', '>=', request()->complete_start_date);
            }
            if (!empty(request()->complete_end_date)) {
                $query->whereDate('order_services.completed_at', '<=', request()->complete_end_date);
            }
            if (!empty(request()->complete_start_time)) {
                $query->where('order_services.completed_at', '>=', request()->complete_start_date . ' ' . Carbon::parse(request()->complete_start_time)->format('H:i:s'));
            }
            if (!empty(request()->complete_end_time)) {
                $query->where('order_services.completed_at', '<=', request()->complete_end_date . ' ' . Carbon::parse(request()->complete_end_time)->format('H:i:s'));
            }

            return DataTables::of($query)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->editColumn('updated_at', '{{@format_datetime($updated_at)}}')
                ->editColumn('suggested_price', '{{@num_format($suggested_price)}}')
                ->editColumn('grand_total', '{{@num_format($grand_total)}}')
                ->editColumn('discount_amount', '{{@num_format($discount_amount)}}')
                ->editColumn('final_total', '{{@num_format($final_total)}}')
                ->editColumn('payment_method', function ($row) {
                   $payment_method= $row->payment_method?:'not_pay';
                    return '<span class="payment_method'.$payment_method.'">' . __('lang.'.$payment_method).'</span>';
                })
                ->editColumn('canceled_by', function ($row) {
                    if (!in_array($row->status,['declined','canceled']))
                        return'';

                    $type=$row->canceled_type;
                    $name=$row->canceledby?->name;
                    return __('lang.'.$type).' => '.$name;
                })
                ->editColumn('cancel_reason', function ($row) {
                return $row->cancel_reason? $row->cancel_reason->title:'';
                })
                ->editColumn('payment_method', function ($row) {
                   $payment_method= $row->payment_method?:'not_pay';
                    return '<span class="payment_method'.$payment_method.'">' . __('lang.'.$payment_method).'</span>';
                })
                ->editColumn('status', function ($row) {
                    switch ($row->status){
                        case 'pending':
                            $class='pending';
                            break;
                        case 'approved':
                        case 'PickUp':
                            $class='approved';
                            break;
                        case 'completed':
                        case  'received':
                            $class='completed';
                            break;

                        case 'canceled':
                        case 'declined':
                            $class='canceled';
                            break;
                    }
                    $html ='<span class="span-status '.$class.'"> '.__('lang.'.$class).'</span>';

                    return $html;
                })
                ->addColumn('image', function ($row) {
                    $images = $row->getMedia('images');
                    $html='';
                    foreach ($images as $image) {
                        $html.= '<div class="image-order" ><img src="' . $image->getUrl() . '" height="50px" width="50px"></div>';
                    }
                    return$html;
                })
                ->addColumn('service_title', function ($row) {
                        return $row->category?->title;
                })
//                ->addColumn('client_name', function ($row) {
//                        return $row->category?->title;
//                })->addColumn('client_phone', function ($row) {
//                        return $row->category?->title;
//                })->addColumn('provider_name', function ($row) {
//                        return $row->category?->title;
//                })->addColumn('provider_phone', function ($row) {
//                        return $row->category?->title;
//                })
                ->addColumn(
                    'action',
                    function ($row) {
                        $html = ' <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">' . __('lang.action') . '
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';

                        $html .= '<li class="divider"></li>';
                        if($row->isOfferPrice() && $row->status == 'pending'  ){


    //                        if (auth()->user()->can('customer_module.customer.edit')){
                            $html .='<li >
                                                        <a data-href = "'. route('admin.order.get-send-offer',  ['order_id'=>$row->id]).'"
                                                            class="btn-modal" data-container = ".view_modal" ><i
                                                                class="fa fa-money btn" ></i > '. __('lang.send_offer').' </a >
                                                    </li >';

    //                        }
                        }elseif($row->status == 'pending'){
                            $html .='<li >
                                                        <a data-href = "'. route('admin.order.get-send-offer',  ['order_id'=>$row->id]).'"
                                                            class="btn-modal" data-container = ".view_modal" ><i
                                                                class="fa fa-check-circle btn" ></i > '. __('lang.accept').' </a >
                                                    </li >';
                        }

                        $html .= '</ul></div>';
                        return $html;
                    }
                )
                ->rawColumns([
                    'action',
                    'payment_method',
                    'status',
                    'image',
                    'created_at',
                    'updated_at',
                ])
                ->make(true);
        }


        $categories = Category::listsTranslations('title as name')->pluck('name','id');
        $cities = City::listsTranslations('title as name')->pluck('name','id');
        $areas = Area::listsTranslations('title as name')->pluck('name','id');
        $users = User::pluck('name','id');
        $providers = Provider::pluck('name','id');

        return view('back-end.orders.index')->with([
          'status'=>$status,
          'categories'=>$categories,
          'cities'=>$cities,
          'areas'=>$areas,
          'users'=>$users,
          'providers'=>$providers,
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCanceledProvider($status=null)
    {
        if (request()->ajax()) {

            $query = OrderService::leftJoin('transactions', 'transactions.id', '=', 'order_services.transaction_id')
                ->leftJoin('users', 'users.id', '=', 'order_services.user_id')
                ->Join('cancellation_records', 'cancellation_records.order_service_id', '=', 'order_services.id')
                ->leftJoin('providers', 'providers.id', '=', 'cancellation_records.provider_id')
//                ->wherehas()
                ->select('order_services.*',
                    'cancellation_records.created_at as canceled_at',
                    'cancellation_records.cancel_reason_id as cancellation_cancel_reason_id',
                    'transactions.invoice_no',
                    'transactions.final_total',
                    'transactions.grand_total',
                    'transactions.discount_amount',
                    'transactions.suggested_price',
                    'users.name as client_name',
                    'users.phone as client_phone',
                    'providers.name as provider_name',
                    'providers.phone as provider_phone',
                );



            return DataTables::of($query)
                ->addColumn('canceled_at', '{{@format_datetime($canceled_at)}}')
                ->editColumn('suggested_price', '{{@num_format($suggested_price)}}')
                ->editColumn('grand_total', '{{@num_format($grand_total)}}')
                ->editColumn('discount_amount', '{{@num_format($discount_amount)}}')
                ->editColumn('final_total', '{{@num_format($final_total)}}')
                ->editColumn('cancel_reason', function ($row) {
                    return $row->cancellation_record->cancel_reason? $row->cancellation_record->cancel_reason->title:'';
                })

                ->addColumn('service_title', function ($row) {
                    return $row->category?->title;
                })
                ->rawColumns([
                    'created_at',
                ])
                ->make(true);
        }


        $categories = Category::listsTranslations('title as name')->pluck('name','id');
        $cities = City::listsTranslations('title as name')->pluck('name','id');
        $areas = Area::listsTranslations('title as name')->pluck('name','id');
        $users = User::pluck('name','id');
        $providers = Provider::pluck('name','id');

        return view('back-end.orders.index-canceled-provider')->with([
            'status'=>$status,
            'categories'=>$categories,
            'cities'=>$cities,
            'areas'=>$areas,
            'users'=>$users,
            'providers'=>$providers,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $cities = City::listsTranslations('title as name')->pluck('name','id');
        $categories = Category::listsTranslations('title as name')->pluck('name','id');
        $quick_add = request()->quick_add ?? null;


        if ($quick_add) {
            return view('back-end.orders.quick_add')->with(compact(
                'categories',
                'cities',
                'quick_add'
            ));
        }

        return view('back-end.orders.create')->with(compact(
            'cities',
            'categories',

            'quick_add'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            ['services_from_home' => ['required','string', 'in:1,0']],
            ['OrderService_type' => ['required','string', 'in:OrderService,OrderServiceCenter']],
            ['email' => ['required', 'unique:OrderServices','max:255']],
            ['name' => ['required', 'between:2,200']],
            ['address' => ['required','string', 'max:255']],
            ['lat' => ['required','string', 'max:255']],
            ['long' => ['required','string', 'max:255']],
            ['password' => ['required','confirmed', 'max:150']],
            ['phone' => ['required', 'max:255','unique:OrderServices', 'max:20']],
            ['city_id' => ['required', 'max:255']],
            ['area_id' => ['required', 'max:255']],
            ['categories' => ['required','array']],
            ['categories.*' => ['required', 'integer','exists:categories,id']]

        );

         try {
        DB::beginTransaction();
        $OrderService = OrderService::create([
            "services_from_home" => $request->services_from_home,
            "OrderService_type" => $request->OrderService_type,
            "name" => $request->name,
            "phone" => $request->phone,
            "email" => $request->email,
            "country_id" => 1,
            "city_id" => $request->city_id,
            "area_id" => $request->area_id,
            "password" => Hash::make($request->password),
            "address" => $request->address,
            "long" => $request->long,
            "lat" => $request->lat,
            "email_verified_at" => now(),
            "is_active" => 1,
        ]);
        if($request->has("categories")){
            $OrderService->categories()->sync($request->categories);
        }
        if ($request->has("cropImages") && count($request->cropImages) > 0) {
            foreach ($request->cropImages as $imageData) {
                $extention = explode(";",explode("/",$imageData)[1])[0];
                $image = rand(1,1500)."_image.".$extention;
                $filePath = public_path('uploads/' . $image);
                $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                $OrderService->addMedia($filePath)->toMediaCollection('images');
            }
        }
        $OrderService_id=$OrderService->id;
        DB::commit();
        $output = [
            'success' => true,
            'OrderService_id' => $OrderService_id,
            'msg' => __('lang.success')
        ];
         } catch (\Exception $e) {
             Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
             $output = [
                 'success' => false,
                 'msg' => __('lang.something_went_wrong')
             ];
         }


        return $output;


//        return redirect()->to('OrderService')->with('status', $output);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $OrderService = OrderService::find($id);
        $cities = City::listsTranslations('title as name')->pluck('name','id');
        $areas = Area::where('city_id',$OrderService->city_id)->listsTranslations('title as name')->pluck('name','id');
        $categories = Category::listsTranslations('title as name')->pluck('name','id');
        $OrderService_categories=$OrderService->categories()->pluck('categories.id');
        return view('back-end.orders.edit')->with(compact(
            'OrderService',
            'categories',
            'OrderService_categories',
            'cities',
            'areas',
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            ['email' => ['required', 'unique:OrderServices,email,'.$id,'max:255']],
            ['name' => ['required', 'max:150']],
            ['password' => ['nullable','confirmed', 'max:150']],
            ['phone' => ['required', 'max:255','unique:OrderServices,phone,'.$id, 'max:20']],
            ['city_id' => ['required', 'max:255']],
            ['area_id' => ['required', 'max:255']],
            ['services_from_home' => ['required','string', 'in:1,0']],
            ['OrderService_type' => ['required','string', 'in:OrderService,OrderServiceCenter']],
            ['address' => ['required','string', 'max:255']],
            ['lat' => ['required','string', 'max:255']],
            ['long' => ['required','string', 'max:255']],
            ['categories' => ['required','array']],
            ['categories.*' => ['required', 'integer','exists:categories,id']]
        );

        try {
            DB::beginTransaction();
            $OrderService = OrderService::find($id);
                $OrderService->OrderService_type= $request->OrderService_type;
                $OrderService->services_from_home= $request->services_from_home;
                $OrderService->name=$request->name;
                $OrderService->phone= $request->phone;
                $OrderService->email= $request->email;
                $OrderService->city_id=$request->city_id;
                $OrderService->area_id=$request->area_id;
                $OrderService->address= $request->address;
                $OrderService->lat= $request->lat;
                $OrderService->long= $request->long;

                if($request->has("password")){
                    $OrderService->password=Hash::make($request->password);
                }

                $OrderService->save();
                if (!$request->has('have_image')){
                    $OrderService->clearMediaCollection('images');
                }
            if($request->has("categories")){
                $OrderService->categories()->sync($request->categories);
            }
            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                $OrderService->clearMediaCollection('images');
                foreach ($request->cropImages as $imageData) {
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $OrderService->addMedia($filePath)->toMediaCollection('images');
                }
            }



            DB::commit();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }
        if ($request->ajax()) {
            return $output;
        } else {
            return redirect()->back()->with('status', $output);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $OrderService = OrderService::find($id);
            if ($OrderService){
                $OrderService->clearMediaCollection('images');
                $OrderService->delete();
            }


            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return $output;
    }

    public function update_status(Request $request ){

        try {
            $OrderService=OrderService::find($request->id);
            if(!$OrderService){
                return [
                    'success'=>false,
                    'msg'=>translate('OrderService_not_found')
                ];
            }


            DB::beginTransaction();
            $OrderService->is_active=($OrderService->is_active - 1) *-1;
            $OrderService->save();
            DB::commit();
            return [
                'success'=>true,
                'msg'=>translate('OrderService updated successfully!')
            ];
        }catch (\Exception $e){
            DB::rollback();
            return [
                'success'=>false,
                'msg'=>__('site.same_error')
            ];
        }
    }
    /**
     * delete Image for Customer
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteImage(Request  $request)
    {
        try {
            DB::beginTransaction();
            $OrderService = OrderService::find($request->id);
            if($OrderService){
                $OrderService->clearMediaCollection('images');

                DB::commit();
                $output = [
                    'success' => true,
                    'msg' => __('lang.success')
                ];
            }else{
                $output = [
                    'success' => false,
                    'msg' => __('lang.something_went_wrong')
                ];
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => "File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage()
            ];
        }

        return redirect()->back()->with(['status' => $output]);
    }

    /**
     * Shows   Send Offer Form For order
     *
     * @param  int  $customer_id
     * @return \Illuminate\Http\Response
     */
    public function getSendOffer($order_id)
    {
        if (request()->ajax()) {
            $order = OrderService::find($order_id);
            if ($order){
                $UserRequest=UserRequest::where('order_service_id',$order_id)->first();
                if($UserRequest) {
                    $arr_id=json_decode($UserRequest->providers_id, true);
                    $providers = Provider::wherein('id',$arr_id)->pluck('name','id');
//                    $providers = Provider::wherehas('categories',function ($q) use($order){
//                        return $q->where('categories.id',$order->category_id);
//                    })->pluck('name','id');
                    return view('back-end.orders.partial.send_offer')
                        ->with(compact( 'providers','order'))->render();
                }

            }
        }
        return [
            'success'=>false,
            'msg'=>__('lang.The_request_has_become_unavailable')
        ];

    }

    /**
     * Send Offer for Order
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function SendOffer (Request  $request)
    {
        try {
            $order = OrderService::find($request->order_id);
            if(!$order){
                return [
                    'success'=>false,
                    'msg'=>__('site.same_error')
                ];
            }
            if($order->status != 'pending'){
                return [
                    'success'=>false,
                    'msg'=>__('lang.The_request_has_become_unavailable')
                ];
            }

            $output=  $this->serviceUtil->sendOfferPrice($order,$request->amount,$request->provider_id);
            if($output['success']){
                $this->pushNotof('Order',$order,$order->user_id,2);
            }



        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => "File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage()
            ];
        }

        return $output;
    }
}
