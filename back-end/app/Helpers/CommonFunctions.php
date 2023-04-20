<?php

// ---------------- Api response -------------------
if (!function_exists('responseApi')) {
    function responseApi($status, $message = '', $data = null)
    {
        return response([
            'statsu' => $status == 'success' ? 1 : 0,
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
