<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\AppScreen;
use App\Models\Category;
use App\Models\Company;
use App\Models\OrderService;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

// use App\Models\WagesAndCompensation;

class HomeController extends Controller
{

    public function indexUser()
    {
        $categories= Category::Active()->get();
        $services= Service::Active()->get();
        $companies= Company::get();
        $reviews= Review::get();
        $screens= AppScreen::where('type','User')->get();
//        dd($screens->first()->getFirstMedia('images')->getUrl());
        return view('front-end.home')->with([
            'categories'=>$categories,
            'services'=>$services,
            'companies'=>$companies,
            'reviews'=>$reviews,
            'screens'=>$screens,
        ]);
    }


    public function showInvoice($invoice_no)
    {
        app()->setLocale('ar');
        $order= OrderService::wherehas('transaction',function ($q) use($invoice_no){
            $q->where('transactions.invoice_no', $invoice_no);
        })->firstOrFail();
        return view('front-end.invoice')->with([
            'order'=>$order
        ]);
    }
    public function indexProvider()
    {
        $categories= Category::Active()->get();
        $services= Service::Active()->get();
        $companies= Company::get();
        $reviews= Review::get();
        $screens= AppScreen::where('type','Provider')->get();
//        dd($screens->first()->getFirstMedia('images')->getUrl());
        return view('front-end.provider.home')->with([
            'categories'=>$categories,
            'services'=>$services,
            'companies'=>$companies,
            'reviews'=>$reviews,
            'screens'=>$screens,
        ]);
    }


}
