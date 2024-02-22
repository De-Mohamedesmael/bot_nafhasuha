<?php

// ---------------- Api response -------------------
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

if (!function_exists('responseApi')) {
    function responseApi($code, $message = '', $data = null)
    {
        return response([
            'status' =>'success',
            'code' => $code != null ? $code : 200,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
// ---------------- Api response -------------------
if (!function_exists('responseApiFalse')) {
    function responseApiFalse($code= null, $message = '', $data = null)
    {
        return response([
            'status' => 'failed' ,
            'code' => $code != null ? $code : 500,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
// ---------------- Upload File -------------------
if (!function_exists('uploadFile')) {
    function uploadFile($file, $path)
    {
        $fileName = time() . '-' . $file->getClientOriginalName();
        $file->move($path, $fileName);
        return $fileName;
    }
}

// ---------------- Locales -------------------
if (!function_exists('locales')) {
    function locales()
    {
        return config('app.locales');
    }
}

// ---------------- Admin Type -------------------
if (!function_exists('majorAdmin')) {
    function majorAdmin()
    {
        if (auth('admin')->user()->type == 'major') return true;
        return false;
    }
}

// ---------------- Positions -------------------
if (!function_exists('positions')) {
    function positions()
    {
        return [
            'left' => trans('store.left'),
            'right' => trans('store.right'),
            'center' => trans('store.center'),
        ];
    }
}

// ---------------- Boolean values -------------------
if (!function_exists('booleanValues')) {
    function booleanValues()
    {
        return [
            0 => trans('store.no'),
            1 => trans('store.yes'),
        ];
    }
}

// ---------------- Payment Methods -------------------
if (!function_exists('paymentMethods')) {
    function paymentMethods()
    {
        return [
            'cash' => trans('store.cash'),
            'visa' => trans('store.visa'),
            'coins' => trans('store.coins'),
        ];
    }
}

// ---------------- Menu active -------------------
if (!function_exists('active')) {
    function active($array)
    {
        $route = explode('.', request()->route()->getName())[0];
        if (in_array($route, $array)) return true;
        return false;
    }
}


function generate_qr_code($invoice_no)
{
    try{
        $filename = 'qr_code_'.$invoice_no.'.png';
        if (!Storage::disk('qr')->exists($filename)) {
            $logo = 'assets/images/settings/' . \Settings::get('logo');
            $logoPath = public_path($logo);
            $logoPath= str_replace('back-end/public/', '', $logoPath);
            $link = route('front.invoice.index',$invoice_no);
            if (file_exists($logoPath)) {
                $image =QrCode::format('png')
                    ->merge($logoPath, 0.5, true)
                    ->size(500)
                    ->errorCorrection('H')
                    ->generate($link);
                Storage::disk('qr')->put($filename, $image);

            } else {
                $image =QrCode::format('png')
                    ->size(500)
                    ->errorCorrection('H')
                    ->generate($link);
                Storage::disk('qr')->put($filename, $image);
            }
        }

        return asset('assets/images/qr/'.$filename);
    }catch (\Exception $exception){
        return asset('assets/images/qrcode.png');
    }
}