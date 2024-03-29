<?php

namespace App\Http\Controllers\Api\Providers;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\AreaResource;
use App\Http\Resources\BanksResource;
use App\Http\Resources\CancelReasonResource;
use App\Http\Resources\CategoryFaqResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\TransporterResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\IconResource;
use App\Http\Resources\InfoResource;
use App\Http\Resources\SplashScreenResource;
use App\Http\Resources\UserResource;
use App\Models\Area;
use App\Models\Bank;
use App\Models\CancelReason;
use App\Models\Category;
use App\Models\CategoryFaq;
use App\Models\CategoryFaqTranslation;
use App\Models\City;
use App\Models\ContactUs;
use App\Models\Country;
use App\Models\FaqTranslation;
use App\Models\Icon;
use App\Models\Info;
use App\Models\SplashScreen;
use App\Models\User;
use App\Models\Transporter;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use function App\CPU\translate;


class GeneralController extends ApiController
{
    protected $commonUtil;
    protected $count_paginate = 10;

    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }


    public function countries(Request $request){
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $countries= Country::orderBy('sort', 'Asc');
        if($count_paginate == 'ALL'){
            $countries=  $countries->get();
        }else{
            $countries=  $countries->simplePaginate($count_paginate);
        }
        return responseApi(200,\App\CPU\translate('return_data_success'), CountryResource::collection($countries));
    }
    public function cities(Request $request){
        $validator = validator($request->all(), [
            'country_id' => 'nullable|integer|exists:countries,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $cities= City::orderBy('sort', 'Asc');
        if($request->has('country_id')){
            $cities=$cities->where('country_id',$request->country_id);
        }
        if($count_paginate == 'ALL'){
            $cities=  $cities->get();
        }else{
            $cities=  $cities->simplePaginate($count_paginate);
        }

        return responseApi(200,\App\CPU\translate('return_data_success'), CityResource::collection($cities));
    }


    public function areas(Request $request){
        $validator = validator($request->all(), [
            'city_id' => 'nullable|integer|exists:cities,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $areas= Area::orderBy('sort', 'Asc');
        if($request->has('city_id')){
            $areas=$areas->where('city_id',$request->city_id);
        }
        if($count_paginate == 'ALL'){
            $areas=  $areas->get();
        }else{
            $areas=  $areas->simplePaginate($count_paginate);
        }
        return responseApi(200,\App\CPU\translate('return_data_success'), AreaResource::collection($areas));
    }

    public function splashScreen(){

        $SplashScreen= SplashScreen::orderBy('sort', 'Asc')->get();

        return responseApi(200,\App\CPU\translate('return_data_success'), SplashScreenResource::collection($SplashScreen));
    }
    public function icons(){

        $icons= Icon::get();

        return responseApi(200,\App\CPU\translate('return_data_success'), IconResource::collection($icons));
    }
    public function updateVersion(Request $request){
        $validator = validator($request->all(), [
            'device_type' => 'required|string|in:IOS,Android',
            'current_version' => 'required|string',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        \Settings::set('update_version_Provider_IOS', '1.1');
        \Settings::set('update_version_Provider_Android', '1.1');
       if( \Settings::get('update_version_Provider_'.$request->device_type) <= $request->current_version){
           return responseApi(200,\App\CPU\translate('return_data_success'),true);
       }
        return responseApi(200,\App\CPU\translate('return_data_success'),false);

    }

    public function faqs(Request $request){
        $validator = validator($request->all(), [
            'text_search' => 'nullable|string',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $CategoryFaq= CategoryFaq::orderBy('sort', 'Asc');
        if($request->has('text_search')){
            $faq_ids= FaqTranslation::where('title','LIKE','%'.$request->text_search.'%')
                ->orwhere('description','LIKE','%'.$request->text_search.'%')->pluck('faq_id');
            $CategoryFaq=$CategoryFaq->with(['faqs'=>function ($q) use ($faq_ids){
                $q->wherein('faqs.id',$faq_ids);
            }])->wherehas('faqs',function ($q) use ($faq_ids){
                $q->wherein('faqs.id',$faq_ids);
            });
        }


        return responseApi(200,\App\CPU\translate('return_data_success'), CategoryFaqResource::collection($CategoryFaq->get()));
    }
    public function infos(Request $request){
        $validator = validator($request->all(), [
            'type' => 'required|string|in:WhoAreWe,termsOfService,privacyPolicy,TermsandConditions',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $Info= Info::where('type',$request->type)->first();



        return responseApi(200,\App\CPU\translate('return_data_success'), new InfoResource($Info));
    }
    public function contactUs(Request $request){
        $validator = validator($request->all(), [
            'title' => 'required|string|max:50',
            'country_id' => 'required|integer|exists:countries,id',
            'phone' => 'required|string|max:20',
            'note' => 'required|string|max:1000',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        ContactUs::create([
            'title' => $request->title,
            'country_id' => $request->country_id,
            'phone' => $request->phone,
            'note' => $request->note,
        ]);

        return responseApi(200,\App\CPU\translate('Your message has been successfully received, and we will get back to you as soon as possible. Thank you for contacting us.'));
    }

    
    public function indexCategories(Request $request)
    {

        $validator = validator($request->all(), [
            'service_id' => 'nullable|integer|exists:services,id'
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $service_id= $request->service_id;
        $categories= Category::Active();
        if($service_id){
            $categories=$categories->wherehas('services',function ($q_serv) use($service_id){
                $q_serv->where('services.id',$service_id);
            });
        }
        if($count_paginate == 'ALL'){
            $categories=  $categories->orderBy('sort', 'Asc')->get();
        }else {
            $categories = $categories->orderBy('sort', 'Asc')->simplePaginate($count_paginate);
        }
        $data['cost_maintenance']= \Settings::get('PriceMaintenance',100);
        $data['categories']= CategoryResource::collection($categories);
        return  responseApi(200, translate('return_data_success'),$data);

    }
    public function transportVehicles(Request $request)
    {
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $transporters= Transporter::Active()->orderBy('sort', 'Asc');
        if($count_paginate == 'ALL'){
            $transporters=  $transporters->get();
        }else {
            $transporters = $transporters->simplePaginate($count_paginate);
        }
        return  responseApi(200, translate('return_data_success'),TransporterResource::collection($transporters));

    }
    public function GetHomeOrCenter(){
        \Settings::set('IFTrueHome',1);
       \Settings::set('IFTrueCenter',1);


        $Home=\Settings::get('IFTrueHome',1);
        $Center=\Settings::get('IFTrueCenter',1);
        $alldata=[];
        if($Home){

            $data['type'] = 'Home';
            $data['title'] = translate('Home');
            array_push($alldata,$data);
        }
        if($Center){
            $data['type'] = 'Center';
            $data['title'] = translate('Center');
            array_push($alldata,$data);

        }

        return responseApi(200,\App\CPU\translate('return_data_success'), $alldata);
    }


    public function banks(Request $request){

        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $banks= Bank::orderByTranslation('title');

        if($count_paginate == 'ALL'){
            $banks=  $banks->get();
        }else{
            $banks=  $banks->simplePaginate($count_paginate);
        }
        return responseApi(200,\App\CPU\translate('return_data_success'), BanksResource::collection($banks));
    }
    public function GetCanceledReasons(){
        $reasons=CancelReason::where('type','Provider')->get();
        $data=CancelReasonResource::collection($reasons);

        return responseApi(200,\App\CPU\translate('return_data_success'), $data);
    }
}

