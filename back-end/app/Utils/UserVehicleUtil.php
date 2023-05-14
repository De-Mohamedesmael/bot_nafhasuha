<?php

namespace App\Utils;

use App\Models\Transaction;
use App\Models\User;
use App\Models\UserVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserVehicleUtil
{


    /**
     * store Vehicle for user
     *
     * @param Request $request
     * @return UserVehicle
     */
    public function SaveVehicle($request)
    {
        $user = auth()->user();
        $Vehicle= UserVehicle::create([
            'user_id'=>$user->id,
            'vehicle_type_id'=>$request->vehicle_type_id,
            'vehicle_brand_id'=>$request->vehicle_brand_id,
            'vehicle_model_id'=>$request->vehicle_model_id,
            'vehicle_manufacture_year_id'=>$request->vehicle_manufacture_year_id,
            'letters_ar'=>$request->letters_ar,
            'letters_en'=>$request->letters_en,
            'numbers_ar'=>$request->numbers_ar,
            'numbers_en'=>$request->numbers_en,
            'periodic_inspection'=>$request->periodic_inspection,
        ]);



        return $Vehicle ;
    }

    /**
     * edit Vehicle for user
     *
     * @param Request $request
     * @param UserVehicle $Vehicle
     * @return UserVehicle
     */
    public function EditVehicle($request,$Vehicle)
    {
        $Vehicle->update([
            'vehicle_type_id'=>$request->vehicle_type_id,
            'vehicle_brand_id'=>$request->vehicle_brand_id,
            'vehicle_model_id'=>$request->vehicle_model_id,
            'vehicle_manufacture_year_id'=>$request->vehicle_manufacture_year_id,
            'letters_ar'=>$request->letters_ar,
            'letters_en'=>$request->letters_en,
            'numbers_ar'=>$request->numbers_ar,
            'numbers_en'=>$request->numbers_en,
            'periodic_inspection'=>$request->periodic_inspection,
        ]);



        return $Vehicle ;
    }

    /**
     * delete Vehicle for user
     *
     * @param UserVehicle $Vehicle
     * @return string
     */
    public function DeleteVehicle($Vehicle)
    {
        $Vehicle->update([
            'status'=>($Vehicle->status - 1 ) * -1,
        ]);
       $m= $Vehicle->status == 1 ? \App\CPU\translate('Your vehicle has been successfully recovered') : \App\CPU\translate('Your vehicle has been successfully deleted');

        return  $m ;
    }



}
