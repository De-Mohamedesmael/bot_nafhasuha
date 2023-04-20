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
        $countries= Country::orderBy('sort', 'desc')->simplePaginate($count_paginate);

        return responseApi('success',\App\CPU\translate('return_data_success'), CountryResource::collection($countries));
    }
    public function cities(Request $request){
        $validator = validator($request->all(), [
            'country_id' => 'nullable|integer|exists:countries,id',
        ]);
        if ($validator->fails())
            return responseApi('false', $validator->errors()->all());

        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $cities= City::orderBy('sort', 'desc');
        if($request->has('country_id')){
            $cities=$cities->where('country_id',$request->country_id);
        }
        $cities=  $cities->simplePaginate($count_paginate);

        return responseApi('success',\App\CPU\translate('return_data_success'), CityResource::collection($cities));
    }


    public function areas(Request $request){
        $validator = validator($request->all(), [
            'city_id' => 'nullable|integer|exists:cities,id',
        ]);
        if ($validator->fails())
            return responseApi('false', $validator->errors()->all());

        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $areas= Area::orderBy('sort', 'desc');
        if($request->has('city_id')){
            $areas=$areas->where('city_id',$request->city_id);
        }
        $areas=  $areas->simplePaginate($count_paginate);

        return responseApi('success',\App\CPU\translate('return_data_success'), AreaResource::collection($areas));
    }
}

