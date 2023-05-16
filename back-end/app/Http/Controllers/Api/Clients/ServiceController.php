<?php

namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\ApiController;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\ProviderServOnlineAllResource;
use App\Http\Resources\ProviderServOnlineResource;
use App\Http\Resources\ServiceResource;
use App\Models\Category;
use App\Models\CategoryService;
use App\Models\Provider;
use App\Models\Service;
use App\Utils\ServiceUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\CPU\translate;

class ServiceController extends ApiController
{
    protected $ServiceUtil, $count_paginate = 10;

    public function __construct(ServiceUtil $ServUtil)
    {
        $this->ServiceUtil=$ServUtil;
    }

    public function index(Request $request)
    {

        $services= Service::Active()->orderBy('sort', 'Asc')->get();
        return  responseApi(200, translate('return_data_success'),ServiceResource::collection($services));

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
        return  responseApi(200, translate('return_data_success'),CategoryResource::collection($categories));

    }
    public function ProviderMapAll(Request $request)
    {
        $validator = validator($request->all(), [
            'service_id' => 'nullable|integer|exists:services,id',
            'lat' => 'required|string',
            'long' => 'required|string',
//            'count_paginate' => 'nullable|integer',
            'sort_type' => 'nullable|in:Asc,Desc',
            'sort_by' => 'nullable|string|in:distance,totalRate,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $sortBy=$request->sort_by??'totalRate';
        $sortType=$request->sort_type??'Desc';
        $service_id=$request->service_id;
        $max_distance=$request->max_distance;
        $min_distance=$request->min_distance;
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $providers = $this->ServiceUtil->getProviderForMap($request->lat,$request->long,$sortBy,$sortType,
            $count_paginate,$service_id,$max_distance,$min_distance);

        return  responseApi(200, translate('return_data_success'),ProviderServOnlineResource::collection($providers));

    }

    public function ProviderMap(Request $request)
    {
        $validator = validator($request->all(), [
            'service_id' => 'nullable|integer',
            'lat' => 'required|string',
            'long' => 'required|string',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $lat=$request->lat;
        $long=$request->long;
        $service_id=$request->service_id;
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));


        $max_distance = \Settings::get('max_distance',500);
        $sqlDistance = DB::raw('( 111.045 * acos( cos( radians(' .$lat . ') )
       * cos( radians( `lat` ) )
       * cos( radians( `long` )
       - radians(' . $long  . ') )
       + sin( radians(' . $lat  . ') )
       * sin( radians( `lat` ) ) ) )');
        $providers = Provider::Active()->select('id','name','provider_type','lat','long')
            ->selectRaw("{$sqlDistance} as distance")
            ->having('distance', '<=', $max_distance);
        if($service_id){
            $providers=  $providers->wherehas('categories',function ($q) use($service_id){
                $q->wherehas('services',function ($q_serv) use($service_id){
                    $q_serv->where('services.id',$service_id);
                });
            });
        }
        $providers= $providers
            ->get();

        return  responseApi(200, translate('return_data_success'),ProviderServOnlineResource::collection($providers));

    }
    public function ViewProviderMap($provider_id,Request $request)
    {
        $validator = validator($request->all(), [
            'lat' => 'required|string',
            'long' => 'required|string',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

//        dd($provider);
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));


        $provider = Provider::find($provider_id);
            if(!$provider)
                return responseApi(404, translate("Page Not Found.If error persists,contact info@gmail.com"));


        $provider = $this->ServiceUtil->getOneProviderForMap($request->lat,$request->long,$provider_id);

        return  responseApi(200, translate('return_data_success'),new ProviderServOnlineAllResource($provider));

    }
}
