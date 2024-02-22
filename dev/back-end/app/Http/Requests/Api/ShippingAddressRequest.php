<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ShippingAddressRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'zone' => 'required|string|max:100',
            'street' => 'required|string|max:100',
            'building' => 'required|string|max:100',
        ];
    }
}
