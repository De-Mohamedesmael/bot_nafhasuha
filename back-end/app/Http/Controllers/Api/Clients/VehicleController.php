<?php

namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserVehicleResource;
use App\Http\Resources\VehicleManufactureYearResource;
use App\Http\Resources\VehicleTypeResource;
use App\Http\Resources\VehicleModelResource;
use App\Http\Resources\VehicleDefaultResource;
use App\Models\UserVehicle;
use App\Models\Vehicle;
use App\Models\VehicleManufactureYear;
use App\Models\VehicleModel;
use App\Models\VehicleType;
use App\Utils\UserVehicleUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function App\CPU\translate;

class VehicleController extends ApiController
{
    protected $UserVehicleUtil;
    public function __construct(UserVehicleUtil $UserVehUtil)
    {
        $this->UserVehicleUtil=$UserVehUtil;
    }

    public function MyrVehicle(Request $request)
    {
        if(!auth()->check())
            return responseApi(403, translate('Unauthenticated user'));

        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $User_vehicles= UserVehicle::Active()->get();

        return responseApi(200,\App\CPU\translate('return_data_success'), UserVehicleResource::collection($User_vehicles));

    }

    public function StoreUserVehicle(Request $request)
    {
        $validator = validator($request->all(), [
            'vehicle_type_id' => 'required|integer|exists:vehicle_types,id',
            'vehicle_id' => 'nullable|integer|exists:vehicles,id',
            'vehicle_model_id' => 'required|integer|exists:vehicle_models,id',
            'vehicle_manufacture_year_id' => 'required|integer|exists:vehicle_manufacture_years,id',
            'title' => 'nullable|string|max:200',
            'letters_ar' => 'required|string|max:10',
            'letters_en' => 'required|string|max:10',
            'numbers_ar' => 'required|string|max:10',
            'numbers_en' => 'required|string|max:10',
            'periodic_inspection' => 'nullable|integer',
            'description' => 'nullable|string|max:1000',
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ]);

        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        DB::beginTransaction();
        try {

           $Vehicle= $this->UserVehicleUtil->SaveVehicle($request);
            if ($request->hasFile('images')) {
                if ($request->hasFile('images')) {
                    $files = $request->file('images');
                    foreach ($files as $file) {
                        $extension = $file->getClientOriginalExtension();
                        $Vehicle->addMedia($file)
                            ->usingFileName(time() . '.' . $extension)
                            ->toMediaCollection('images');
                    }
                }

//                $Vehicle->addMultipleMediaFromRequest($request->file('images'))->toMediaCollection('images');
            }

            DB::commit();
            return responseApi(200,\App\CPU\translate('The creation of your vehicle was successful. Thank you.'), new UserVehicleResource($Vehicle));
        }catch (\Exception $exception){
            DB::rollBack();
            return$exception ;
            Log::emergency('File: ' . $exception->getFile() . 'Line: ' . $exception->getLine() . 'Message: ' . $exception->getMessage());
            return responseApiFalse(500, translate('Something went wrong'));
        }
    }



    /******************* get data ************/
    public function manufactureYears(Request $request)
    {
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $VehicleManufactureYears= VehicleManufactureYear::orderBy('title');
        if($count_paginate == 'ALL'){
            $VehicleManufactureYears=  $VehicleManufactureYears->get();
        }else{
            $VehicleManufactureYears=  $VehicleManufactureYears->simplePaginate($count_paginate);
        }
        return responseApi(200,\App\CPU\translate('return_data_success'), VehicleManufactureYearResource::collection($VehicleManufactureYears));

    }
    public function VehicleTypes(Request $request)
    {
        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $VehicleTypes= VehicleType::Active()->orderByTranslation('title');
        if($count_paginate == 'ALL'){
            $VehicleTypes=  $VehicleTypes->get();
        }else{
            $VehicleTypes=  $VehicleTypes->simplePaginate($count_paginate);
        }
        return responseApi(200,\App\CPU\translate('return_data_success'), VehicleTypeResource::collection($VehicleTypes));

    }
    public function VehicleModels(Request $request)
    {
        $validator = validator($request->all(), [
            'type_id' => 'required|integer|exists:vehicle_types,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $VehicleModels= VehicleModel::Active()->where('vehicle_type_id',$request->type_id)->
        orderByTranslation('title');
        if($count_paginate == 'ALL'){
            $VehicleModels=  $VehicleModels->get();
        }else{
            $VehicleModels=  $VehicleModels->simplePaginate($count_paginate);
        }
        return responseApi(200,\App\CPU\translate('return_data_success'), VehicleModelResource::collection($VehicleModels));

    }
    public function VehicleDefault(Request $request)
    {
        $validator = validator($request->all(), [
            'type_id' => 'required|integer|exists:vehicle_types,id',
        ]);
        if ($validator->fails())
            return responseApiFalse(405, $validator->errors()->first());

        $count_paginate=$request->count_paginate?:$this->count_paginate;
        $Vehicles= Vehicle::Active()->where('vehicle_type_id',$request->type_id)->
        orderByTranslation('title');
        if($count_paginate == 'ALL'){
            $Vehicles=  $Vehicles->get();
        }else{
            $Vehicles=  $Vehicles->simplePaginate($count_paginate);
        }
        return responseApi(200,\App\CPU\translate('return_data_success'), VehicleDefaultResource::collection($Vehicles));

    }


}



