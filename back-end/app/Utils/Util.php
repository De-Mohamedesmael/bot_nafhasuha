<?php

namespace App\Utils;

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
         * @param User $user
         * @param string $type
     * @return boolean
     */
    public function SendActivationCode($user,$type)
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

    /**
     * converty currency base on exchange rate
     *
     * @param User $user
     * @param string $code
     * @return boolean
     */
    public function SetInvitationCode($user,$code)
    {

        return true;
    }
}
