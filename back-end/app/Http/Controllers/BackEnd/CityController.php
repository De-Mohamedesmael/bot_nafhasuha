<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Resources\AreaResource;
use App\Models\Area;
use App\Models\City;
use App\Models\USer;
use App\Models\System;
use App\Models\Transaction;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
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
    public function areas(Request $request){
        $validator = validator($request->all(), [
            'city_id' => 'nullable|integer|exists:cities,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $areas= Area::orderBy('sort', 'Asc');
        if($request->has('city_id')){
            $areas=$areas->where('city_id',$request->city_id);
        }

            $areas=  $areas->get();

        return responseApi(200,\App\CPU\translate('return_data_success'), AreaResource::collection($areas));
    }
}
