<?php


namespace App\Traits;


class ResponseApi
{

    public function responseApi($status, $data = null, $message = '')
    {
        return response([
            'status' => $status,
            'data' => $data,
            '$message' => $message,
        ]);
    }

}
