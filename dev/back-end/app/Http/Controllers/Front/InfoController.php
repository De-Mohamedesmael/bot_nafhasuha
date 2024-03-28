<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\web\ContactUsRequest;
use App\Http\Requests\web\SubscriptionRequest;
use App\Models\ContactUs;
use App\Models\Faq;
use App\Models\Icon;
use App\Models\Info;
use App\Models\Subscription;
use DB;

class InfoController extends Controller
{


    public  function show($slug){

        $Info=Info::where('slug',$slug)->firstOrFail();
        $data=[];
        switch ($slug){
            case 'faq':
                $view='front-end.infos.faq';
                $data=Faq::get();
                break;
            default:
                $view='front-end.infos.show';
        }
        return view($view)->with([
            'Info'=>$Info,
            'data'=>$data,
        ]);
    }

    public  function ContactUs(){
        $icons= Icon::get();
        return view('front.contact_us',compact('icons'));
    }
    public  function storeContactUs(ContactUsRequest $request){
        DB::beginTransaction();
        try {
            ContactUs::create([
                'title'=>$request->get("contact-name") ,
                'country_id'=>1 ,
                'phone'=>$request->get("contact-phone"),
//                'email'=>$request->get("contact-email"),
                'note'=>$request->get("contact-message") ,
            ]);
            DB::commit();
            return redirect()->back()->with('success',__('site.StoreContactUs_done'));

        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('error',__('site.same_error'));
        }
    }
    public  function storeSubscribe(SubscriptionRequest $request){
        DB::beginTransaction();
        try {
            if(Subscription::where('email',$request->get("email"))->first()){

                return redirect()->back()->with('error',__('site.storeSubscribe_error'));
            }

            Subscription::create([
                'email'=>$request->get("email"),
            ]);
            DB::commit();
            return redirect()->back()->with('success',__('site.storeSubscribe_done'));

        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('error',__('site.same_error'));
        }
    }



}
