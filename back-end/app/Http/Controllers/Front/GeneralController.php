<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ApiController;
use App\Http\Resources\CategoryFaqResource;
use App\Http\Resources\Front\CategoryResource;
use App\Http\Resources\IconResource;
use App\Http\Resources\InfoResource;
use App\Http\Resources\Front\SplashScreenResource;
use App\Http\Resources\Front\ServiceResource;
use App\Models\AppScreen;
use App\Models\Category;
use App\Models\CategoryFaq;
use App\Models\ContactUs;
use App\Models\FaqTranslation;
use App\Models\Icon;
use App\Models\Info;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\System;
use App\Utils\Util;
use Illuminate\Http\Request;
use function App\CPU\translate;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class GeneralController extends ApiController
{
    protected $commonUtil;
    protected $count_paginate = 10;

    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }



    public function splashScreen(Request $request){

        $validator = validator($request->all(), [
            'type' => 'required|string|in:User,Provider',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());
        $SplashScreen= AppScreen::where('type',$request->type)->get();
        return responseApi(200,\App\CPU\translate('return_data_success'), SplashScreenResource::collection($SplashScreen));
    }
    public function icons(){

        $icons= Icon::get();

        return responseApi(200,\App\CPU\translate('return_data_success'), IconResource::collection($icons));
    }
    public function Categories(Request $request)
    {
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $categories= Category::Active();
        if($count_paginate == 'ALL'){
            $categories=  $categories->orderBy('sort', 'Asc')->get();
        }else {
            $categories = $categories->orderBy('sort', 'Asc')->simplePaginate($count_paginate);
        }
        $data= CategoryResource::collection($categories);
        return  responseApi(200, translate('return_data_success'),$data);

    }
    public function Services(Request $request)
    {
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $categories= Service::Active();
        if($count_paginate == 'ALL'){
            $categories=  $categories->orderBy('sort', 'Asc')->get();
        }else {
            $categories = $categories->orderBy('sort', 'Asc')->simplePaginate($count_paginate);
        }
        $data= ServiceResource::collection($categories);
        return  responseApi(200, translate('return_data_success'),$data);

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
    public  function storeSubscribe(Request $request){
        DB::beginTransaction();
        try {
            $validator = validator($request->all(), [
                'email' => 'required|max:200|email',
            ]);

            if ($validator->fails())
                return responseApiFalse(405, $validator->errors()->first());

            if(Subscription::where('email',$request->get("email"))->first()){
                return responseApiFalse(405,__('site.storeSubscribe_error'));
            }

            Subscription::create([
                'email'=>$request->get("email"),
            ]);
            DB::commit();
            return responseApi(200,__('site.storeSubscribe_done'));

        }catch (\Exception $e){
            DB::rollback();
            return responseApiFalse(405,__('site.same_error'));
        }
    }

    public function SettingData($type){
         $logo= \Settings::get('logo','');
        $data['logo']=$logo != null ? asset('assets/images/settings/'.$logo) :asset('assets/front-end/public/images/logo.svg');
        $logo_footer = \Settings::get('logo_footer');
        $data['logo_footer'] = $logo_footer != null ? asset('assets/images/settings/'.$logo_footer) :asset('assets/front-end/public/images/footer_logo.svg');
        $data['whatsapp_numbers'] = System::getProperty('watsapp_numbers','+96650556408');
        $data['welcome_messages'] = \Settings::get('welcome_messages_{$type}_'.app()->getLocale(),"يعد موقع نفحصها نظاماً متكاملاً , قدرته فائقة لتلبي معظم إحتياجات مراكز الخدمة بكل
                            أنواعها مع
                            اختلاف أحجامها. كما يتميز البرنامج بسهولة الاستخدام والإنسيابية , مما يحسن من أداء العاملين
                            بالمركز , وبالتالى يرفع من كفاءة ومستوى الخدمة. و يوفر هذا النظام الأدوات الأساسية لإدارة
                            متكاملة بشكل احترافى متميز محاسبياً و إدارياً , بأعلي");
        $data['counter']['happy_customers']= \Settings::get('happy_customers',120);
        $data['counter']['Cars_repaired']= \Settings::get('Cars_repaired',500);
        $data['counter']['recovery_vehicle']= \Settings::get('recovery_vehicle',80);
        $data['counter']['Number_of_workshops_we_have']= \Settings::get('Number_of_workshops_we_have',80);
        $data['image_our_partners'] = \Settings::get('image_review_code') ? asset('assets/images/settings/'.\Settings::get('image_review_code')) :asset('assets/front-end/public/images/review_code.svg');
        $data['booster_video'] = \Settings::get('booster_video_{$type}','https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.jpg');
        $data['video'] = \Settings::get('video_{$type}','https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4');


        $data['app_screen_title'] = \Settings::get('app_screen_{$type}_title','تطبيق نفحصها لكل خدمات صيانة السيارات');
        $data['app_screen_desc'] =\Settings::get('app_screen_{$type}_desc','يعد تطبيق نفحصها نظاماً متكاملاً , قدرته فائقة لتلبي معظم إحتياجات مراكز الخدمة بكل
                            أنواعها مع
                            اختلاف أحجامها. كما يتميز البرنامج بسهولة الاستخدام والإنسيابية , مما يحسن من أداء');
        $data['app_download_title'] =\Settings::get('app_download_{$type}_title','لننزل تطبيق 😍 نفحصها ولا تنسى الإعجاب 👍🏻 وانتظر رأيك لكتابته ✍🏻 على متجر جوجل بلاي لتطوير أنفسنا أكثر');
        $data['app_download_app_google'] =\Settings::get('app_download_{$type}_google','#');
        $data['app_download_app_store'] = \Settings::get('app_download_{$type}_app_store','#');
        $data['location'] = \Settings::get('location',' الرياض شارع الصفا عمارة الصفا');
        $data['mobile_support'] =\Settings::get('mobile_support','+02345768896');
        $data['mobile_support2'] =\Settings::get('mobile_support2','+02345768896');
        $data['email_support'] =\Settings::get('email_support','test@test.com');

        return responseApi(200,\App\CPU\translate('return_data_success'), $data);
    }

}

