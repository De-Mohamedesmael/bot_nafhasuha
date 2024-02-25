<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\ProviderRate;
use App\Models\Slider;
use App\Models\Provider;
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

class ProviderController extends Controller
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
            $providers = Provider::withAvg('rates as totalRate', 'rate');

            return DataTables::of($providers)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->editColumn('totalRate', '{{@number_format($totalRate,1)}}')
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
                    return $this->transactionUtil->getWalletProviderBalance($row);
                })->addColumn('city', function ($row) {
                    return $row->city?->title;
                })
                ->addColumn(
                    'action',
                    function ($row) {
                        $html = '';

//                        if (auth()->user()->can('provider_module.provider.add_balen')){
                        $html .='<a class="a-image" href="'. route('admin.provider.edit',$row->id) .'" target="_blank" title="'.__('lang.edit').'">
                                           <img class="icon-action" src="'.asset('assets/back-end/images/design/edit.svg').'">
                              </a>
                                            ';

//                        }

//                        if (auth()->user()->can('provider_module.provider.add_balen')){
                        $html .=' <a href="'. route('admin.provider.view_rate',$row->id) .'" title="'.__('lang.view_rate_list').'"><i
                                                        class="dripicons-star"></i></a>
                                            ';

//                        }
                        $html .= '';

//                            if (auth()->user()->can('provider_module.provider.delete')) {
                                $html .=
                                    '
                                    <a data-href="' . route('admin.provider.delete', $row->id)  . '"
                                        data-check_password="' . route('admin.checkPassword', Auth::user()->id) . '"
                                        class="btn text-red delete_item" title="'.__('lang.delete').'"><i class="dripicons-trash"></i>

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
                ])
                ->make(true);
        }

        return view('back-end.provider.index');
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
            return view('back-end.provider.quick_add')->with(compact(
                'categories',
                'cities',
                'quick_add'
            ));
        }

        return view('back-end.provider.create')->with(compact(
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
            ['provider_type' => ['required','string', 'in:Provider,ProviderCenter']],
            ['email' => ['required', 'unique:providers','max:255']],
            ['name' => ['required', 'between:2,200']],
            ['address' => ['required','string', 'max:255']],
            ['lat' => ['required','string', 'max:255']],
            ['long' => ['required','string', 'max:255']],
            ['password' => ['required','confirmed', 'max:150']],
            ['phone' => ['required', 'max:255','unique:providers', 'max:20']],
            ['city_id' => ['required', 'max:255']],
            ['area_id' => ['required', 'max:255']],
            ['categories' => ['required','array']],
            ['categories.*' => ['required', 'integer','exists:categories,id']]

        );

         try {
        DB::beginTransaction();
        $provider = Provider::create([
            "services_from_home" => $request->services_from_home,
            "provider_type" => $request->provider_type,
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
            $provider->categories()->sync($request->categories);
        }
        if ($request->has("cropImages") && count($request->cropImages) > 0) {
            foreach ($request->cropImages as $imageData) {
                $extention = explode(";",explode("/",$imageData)[1])[0];
                $image = rand(1,1500)."_image.".$extention;
                $filePath = public_path('uploads/' . $image);
                $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                $provider->addMedia($filePath)->toMediaCollection('images');
            }
        }
        $provider_id=$provider->id;
        DB::commit();
        $output = [
            'success' => true,
            'provider_id' => $provider_id,
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


//        return redirect()->to('provider')->with('status', $output);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $provider = Provider::find($id);
        $cities = City::listsTranslations('title as name')->pluck('name','id');
        $areas = Area::where('city_id',$provider->city_id)->listsTranslations('title as name')->pluck('name','id');
        $categories = Category::listsTranslations('title as name')->pluck('name','id');
        $provider_categories=$provider->categories()->pluck('categories.id');
        return view('back-end.provider.edit')->with(compact(
            'provider',
            'categories',
            'provider_categories',
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
            ['email' => ['required', 'unique:providers,email,'.$id,'max:255']],
            ['name' => ['required', 'max:150']],
            ['password' => ['nullable','confirmed', 'max:150']],
            ['phone' => ['required', 'max:255','unique:providers,phone,'.$id, 'max:20']],
            ['city_id' => ['required', 'max:255']],
            ['area_id' => ['required', 'max:255']],
            ['services_from_home' => ['required','string', 'in:1,0']],
            ['provider_type' => ['required','string', 'in:Provider,ProviderCenter']],
            ['address' => ['required','string', 'max:255']],
            ['lat' => ['required','string', 'max:255']],
            ['long' => ['required','string', 'max:255']],
            ['categories' => ['required','array']],
            ['categories.*' => ['required', 'integer','exists:categories,id']]
        );

        try {
            DB::beginTransaction();
            $provider = Provider::find($id);
                $provider->provider_type= $request->provider_type;
                $provider->services_from_home= $request->services_from_home;
                $provider->name=$request->name;
                $provider->phone= $request->phone;
                $provider->email= $request->email;
                $provider->city_id=$request->city_id;
                $provider->area_id=$request->area_id;
                $provider->address= $request->address;
                $provider->lat= $request->lat;
                $provider->long= $request->long;

                if($request->has("password")){
                    $provider->password=Hash::make($request->password);
                }

                $provider->save();
                if (!$request->has('have_image')){
                    $provider->clearMediaCollection('images');
                }
            if($request->has("categories")){
                $provider->categories()->sync($request->categories);
            }
            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                $provider->clearMediaCollection('images');
                foreach ($request->cropImages as $imageData) {
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $provider->addMedia($filePath)->toMediaCollection('images');
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
            $provider = Provider::find($id);
            if ($provider){
                $provider->clearMediaCollection('images');
                $provider->delete();
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
            $provider=Provider::find($request->id);
            if(!$provider){
                return [
                    'success'=>false,
                    'msg'=>translate('provider_not_found')
                ];
            }


            DB::beginTransaction();
            $provider->is_active=($provider->is_active - 1) *-1;
            $provider->save();
            DB::commit();
            return [
                'success'=>true,
                'msg'=>translate('Provider updated successfully!')
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
            $provider = Provider::find($request->id);
            if($provider){
                $provider->clearMediaCollection('images');

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
     * @param  int  $provider_id
     * @return \Illuminate\Http\Response
     */
    public function getPay($provider_id)
    {
        if (request()->ajax()) {
            $provider = Provider::find($provider_id);
            if ($provider){
                $getWalletProviderBalance = $this->transactionUtil->getWalletProviderBalance($provider);
                return view('back-end.provider.partial.pay_provider')
                    ->with(compact( 'getWalletProviderBalance','provider'));
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
            $this->transactionUtil->addWalletBalanceCustomer($request->provider_id,$request->amount,'Admin',\auth()->id(),$request->paid_on);
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
    public function getWallet(Request $request ){

        try {
            $provider=Provider::find($request->provider_id);

            if(!$provider){

                return [
                    'success'=>false,
                    'msg'=>translate('provider_not_found')
                ];
            }

            $getWalletProviderBalance = $this->transactionUtil->getWalletProviderBalance($provider);

            return [
                'success'=>true,
                'wallet'=>$getWalletProviderBalance,
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewRate ($provider_id)
    {
        if (request()->ajax()) {
            $logo=\Settings::get('logo');
            $rates = ProviderRate::where('provider_id',$provider_id)
                ->leftjoin('users', 'provider_rates.user_id', 'users.id')
                ->select('provider_rates.*',
                    'users.name',
                    'users.phone',
                );

            return DataTables::of($rates)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->editColumn('rate', '{{@number_format($rate,1)}}')

                ->addColumn(
                    'action',
                    function ($row) {
                        $html = '';


//                            if (auth()->user()->can('provider_module.provider.rate.delete')) {
                        $html .=
                            '
                                    <a data-href="' . route('admin.provider.rate_delete', $row->id)  . '"
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
                    'created_at',
                ])
                ->make(true);
        }
        $provider = Provider::find($provider_id);
        return view('back-end.provider.rates.index',compact('provider_id','provider'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rateDelete($id)
    {
        try {
            $provider = ProviderRate::find($id);
            if ($provider){
                $provider->delete();
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
}
