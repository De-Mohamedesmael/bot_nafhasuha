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
        $user->activation_code=rand(1000, 9999);
        $user->save();
        $phones=["966{$user->phone}"];
        $ms=__('translation.activation_code',['code'=>$user->activation_code]);
        $data=$this->SendSMS($ms,$phones);
        switch ($type){
            case 'Reset':
                break;
            default:
        }

        return $data;
    }



    /**
     * converty currency base on exchange rate
     *
     * @param String $text
     * @param array $numbers
     * @return array
     */
    public function SendSMS($text,$numbers)
    {
        $app_id = env('SMS_API_KEY');
        $app_sec = env('SMS_API_SEC');
        $app_sender = env('SMS_API_SENDER');
        $app_hash = base64_encode("{$app_id}:{$app_sec}");

        $messages = [
            "messages" => [
                [
                    "text" => "{$text}",
                    "numbers" => $numbers,
                    "sender" => "{$app_sender}"
                ]
            ]
        ];

        $url = "https://api-sms.4jawaly.com/api/v1/account/area/sms/send";
        $headers = [
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Basic {$app_hash}"
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($messages));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $response_json = json_decode($response, true);
        if ($status_code == 200) {
            if (isset($response_json["messages"][0]["err_text"])) {
                return [
                    'status'=>false,
                    'message'=>$response_json["messages"][0]["err_text"],
                ];

            } else {
                return [
                    'status'=>true,
                    'message'=>'',
                ];
            }
        } elseif ($status_code == 400) {
            return [
                'status'=>false,
                'message'=>$response_json["message"],
            ];
        } elseif ($status_code == 422) {
            return [
                'status'=>false,
                'message'=> translate('message_not_found'),
            ];
        }

        return [
            'status'=>false,
            'message'=>  "محظور بواسطة كلاودفلير. Status code: {$status_code}"
        ];


    }
    /**
     * get Payment Type Array
     *
     * @return array
     */
    public function getPaymentTypeArray()
    {
        return [
            'Online'=>__('lang.Online'),
            'Cash'=>__('lang.Cash'),
            'Wallet'=>__('lang.Wallet')
        ];
    }

}
