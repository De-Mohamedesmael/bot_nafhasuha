<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Front\SplashScreenResource;
use App\Http\Resources\Front\ReviewResource;
use App\Models\AppScreen;
use App\Models\Review;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function App\CPU\translate;


class ReviewController extends ApiController
{
    protected $commonUtil;
    protected $count_paginate = 10;

    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }



    public function index(Request $request){

        $reviews= Review::get();
        return responseApi(200,\App\CPU\translate('return_data_success'), ReviewResource::collection($reviews));
    }
    public function store(Request $request)
    {

        $validator = validator($request->all(), [
            'name' => 'required|string',
            'comment' => 'required|string',
            'image' => 'required|image',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        try {
            DB::beginTransaction();
            $splash_screen =  Review::create([
                'name'=>$request->name,
                'comment'=>$request->comment
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

