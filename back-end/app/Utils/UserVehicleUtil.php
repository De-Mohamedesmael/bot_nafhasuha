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
     * Save Invitation Bonus  in Transaction Table for user
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



}
