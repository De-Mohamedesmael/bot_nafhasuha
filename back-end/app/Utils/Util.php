<?php

namespace App\Utils;

use App\Models\Transaction;
use App\Models\User;

class Util
{
    /**
     * converty currency base on exchange rate
     *
//     * @param float $amount
     * @return boolean
     */
    public function CreateTransaction()
    {
        return true;
    }
    /**
     * converty currency base on exchange rate
     *
         * @param object $user
         * @param string $type
         * @param string $auth_type
     * @return boolean
     */
    public function SendActivationCode($user,$type,$auth_type="User")
    {
        $user->activation_code=1111;
        $user->save();
        switch ($type){
            case 'Reset':

                break;
            default:


        }

        return true;
    }

}
