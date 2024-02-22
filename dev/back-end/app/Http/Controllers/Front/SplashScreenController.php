<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ApiController;
use App\Http\Resources\CategoryFaqResource;
use App\Http\Resources\Front\CategoryResource;
use App\Http\Resources\IconResource;
use App\Http\Resources\InfoResource;
use App\Http\Resources\Front\SplashScreenResource;
use App\Models\AppScreen;
use App\Models\Category;
use App\Models\CategoryFaq;
use App\Models\ContactUs;
use App\Models\FaqTranslation;
use App\Models\Icon;
use App\Models\Info;
use App\Models\Subscription;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function App\CPU\translate;


class SplashScreenController extends ApiController
{
    protected $commonUtil;
    protected $count_paginate = 10;

    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }



    public function index(Request $request){

        $validator = validator($request->all(), [
            'type' => 'required|string|in:User,Provider',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());
        $SplashScreen= AppScreen::where('type',$request->type)->get();
        return responseApi(200,\App\CPU\translate('return_data_success'), SplashScreenResource::collection($SplashScreen));
    }
    public function store(Request $request)
    {

        $validator = validator($request->all(), [
            'type' => 'required|string|in:User,Provider',
            'image' => 'required|image',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        try {
            DB::beginTransaction();
            $splash_screen =  AppScreen::create([
                'type'=>$request->type
            ]);

            if ($request->has("image")) {
                $splash_screen->addMedia($request->image)->toMediaCollection('images');
            }
            DB::commit();
            return responseApi(200,\App\CPU\translate('return_data_success'), new SplashScreenResource($splash_screen));
        } catch (\Exception $e) {
            dd($e);
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            return responseApiFalse(500, __('lang.something_went_wrong'));

        }
    }

}

