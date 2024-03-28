<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Slider;
use App\Models\User;
use App\Models\System;
use App\Models\Transaction;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use function App\CPU\translate;

class CustomerController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;
    protected $transactionUtil;


    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @param TransactionUtil $transactionUtil
     * @return void
     */
    public function __construct(Util $commonUtil, TransactionUtil $transactionUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->transactionUtil = $transactionUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $logo=\Settings::get('logo');
            $query = User::leftjoin('users as invite', 'users.invite_by', 'invite.id')
                ->select(
                    'users.*',
                    'invite.name as invite_by_name'
                );

            $customers = $query->groupBy('users.id');

            return DataTables::of($customers)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->editColumn('invite_by', '{{$invite_by_name}}')
                ->addColumn('status', function ($row) {
                    $checked=$row->is_active?'checked':'';
                    $html ='<form>  <label> <input class="update_status check" type="checkbox" id="switch'.$row->id.'" data-id="'.$row->id.'" switch="bool" '.$checked.' />
                        <label for="switch'.$row->id.'" data-on-label="'.__('translation.active').'" data-off-label="'.__('translation.inactive').'"></label> <span class="check"></span> </label></form>';

                    return $html;
                })
                ->addColumn('image', function ($row) use($logo) {
                    $image = $row->getFirstMediaUrl('images');
                    if (!empty($image)) {
                        return '<img src="' . $image . '" height="50px" width="50px">';
                    } else {
                        return '<img src="' . asset('/assets/images/settings/' . $logo) . '" height="50px" width="50px">';
                    }
                })
                ->addColumn('balance', function ($row) {
                    return $this->transactionUtil->getWalletBalance($row);
                })->addColumn('city', function ($row) {
                    return $row->city?->title;
                })
                ->addColumn(
                    'action',
                    function ($row) {

                        $html = '';
//                        if (auth()->user()->can('customer_module.customer.edit')){
                            $html .='
                                                    <a data-href = "'. route('admin.customer.pay',  ['customer_id'=>$row->id]).'"
                                                        class="btn-modal a-image" data-container = ".view_modal" title=" '. __('lang.pay_customer').'" >
                                                         <img class="icon-action" src="'.asset('assets/back-end/images/design/icon-accept-transaction.svg').'">
                                                         </a >
                                                ';

//                        }

//                        if (auth()->user()->can('customer_module.customer.add_balen')){
                        $html .='
                                                <a class="a-image" href="'. route('admin.customer.edit',$row->id) .'" target="_blank" title="'.__('lang.edit').'">
                                                               <img class="icon-action" src="'.asset('assets/back-end/images/design/edit.svg').'">
                                                        </a>
                                            ';

//                        }
                        $html .= '';

//                            if (auth()->user()->can('customer_module.customer.delete')) {
                                $html .=
                                    '
                                    <a data-href="' . route('admin.customer.delete', $row->id)  . '"
                                        data-check_password="' . route('admin.checkPassword', Auth::user()->id) . '"
                                        class="btn text-red delete_item" title="' . __('lang.delete') . '"><i class="dripicons-trash"></i>
                                        </a>
                                    ';
//                            }



                        $html .= '</ul></div>';
                        return $html;
                    }
                )
                ->rawColumns([
                    'action',
                    'status',
                    'image',
                    'balance',
                    'city',
                    'created_at',
                    'invite_by',
                ])
                ->make(true);
        }

        return view('back-end.customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $cities = City::listsTranslations('title as name')->pluck('name','id');
        $quick_add = request()->quick_add ?? null;


        if ($quick_add) {
            return view('back-end.customer.quick_add')->with(compact(
                'cities',
                'quick_add'
            ));
        }

        return view('back-end.customer.create')->with(compact(
            'cities',
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
            ['email' => ['required', 'unique:users','max:255']],
            ['name' => ['required', 'max:150']],
            ['password' => ['required','confirmed', 'max:150']],
            ['phone' => ['required', 'max:255','unique:users', 'max:20']],
            ['city_id' => ['required', 'max:255']],
            ['area_id' => ['required', 'max:255']]
        );

         try {
        DB::beginTransaction();
        $customer = User::create([
            "name" => $request->name,
            "phone" => $request->phone,
            "email" => $request->email,
            "country_id" => 1,
            "city_id" => $request->city_id,
            "area_id" => $request->area_id,
            "password" => Hash::make($request->password),
            "address" => $request->address
        ]);
        if ($request->has("cropImages") && count($request->cropImages) > 0) {
            foreach ($request->cropImages as $imageData) {
                $extention = explode(";",explode("/",$imageData)[1])[0];
                $image = rand(1,1500)."_image.".$extention;
                $filePath = public_path('uploads/' . $image);
                $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                $customer->addMedia($filePath)->toMediaCollection('images');
            }
        }
        $customer_id=$customer->id;
        DB::commit();
        $output = [
            'success' => true,
            'customer_id' => $customer_id,
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


//        return redirect()->to('customer')->with('status', $output);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = User::find($id);
        $cities = City::listsTranslations('title as name')->pluck('name','id');
        $areas = Area::where('city_id',$customer->city_id)->listsTranslations('title as name')->pluck('name','id');
        return view('back-end.customer.edit')->with(compact(
            'customer',
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
            ['email' => ['required', 'unique:users,email,'.$id,'max:255']],
            ['name' => ['required', 'max:150']],
            ['password' => ['nullable','confirmed', 'max:150']],
            ['phone' => ['required', 'max:255','unique:users,phone,'.$id, 'max:20']],
            ['city_id' => ['required', 'max:255']],
            ['area_id' => ['required', 'max:255']]
        );

        try {
            DB::beginTransaction();
            $customer = User::find($id);
                $customer->name=$request->name;
                $customer->phone= $request->phone;
                $customer->email= $request->email;
                $customer->city_id=$request->city_id;
                $customer->area_id=$request->area_id;
                $customer->address= $request->address;

                if($request->has("password")){
                    $customer->password=Hash::make($request->password);
                }

                $customer->save();
                if (!$request->has('have_image')){
                    $customer->clearMediaCollection('images');
                }
            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                $customer->clearMediaCollection('images');
                foreach ($request->cropImages as $imageData) {
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $customer->addMedia($filePath)->toMediaCollection('images');
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
            $customer = User::find($id);
            if ($customer){
                $customer->clearMediaCollection('images');
                $customer->delete();
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
            $user=User::find($request->id);
            if(!$user){
                return [
                    'success'=>false,
                    'msg'=>translate('user_not_found')
                ];
            }


            DB::beginTransaction();
            $user->is_active=($user->is_active - 1) *-1;
            $user->save();
            DB::commit();
            return [
                'success'=>true,
                'msg'=>translate('User updated successfully!')
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
            $customer = User::find($request->id);
            if($customer){
                $customer->clearMediaCollection('images');

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
     * Shows  payment Customer
     *
     * @param  int  $customer_id
     * @return \Illuminate\Http\Response
     */
    public function getPay($customer_id)
    {
        if (request()->ajax()) {
            $customer = User::find($customer_id);
            if ($customer){
                $getWalletBalance = $this->transactionUtil->getWalletBalance($customer);
                return view('back-end.customer.partial.pay_customer')
                    ->with(compact( 'getWalletBalance','customer'));
            }
        }
    }

    /**
     * Adds Payments for Customer
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postPay (Request  $request)
    {
        try {
            DB::beginTransaction();
            $this->transactionUtil->addWalletBalanceCustomer($request->customer_id,$request->amount,'Admin',\auth()->id(),$request->paid_on);
            DB::commit();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
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

}
