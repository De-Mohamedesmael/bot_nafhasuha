<?php

namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\Controller;
use App\Http\Resources\AreaResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\UserResource;
use App\Models\Area;
use App\Models\City;
use App\Models\Country;
use App\Models\User;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class GeneralController extends Controller
{
    protected $commonUtil;
    protected $count_paginate = 10;

    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }


    public function countries(Request $request){
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $countries= Country::orderBy('sort', 'desc');
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
            return responseApi('false', $validator->errors()->first());

        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $cities= City::orderBy('sort', 'desc');
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
            return responseApi('false', $validator->errors()->first());

        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $areas= Area::orderBy('sort', 'desc');
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
}

