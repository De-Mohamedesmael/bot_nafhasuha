<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'zone' => 'required|string|max:100',
            'street' => 'required|string|max:100',
            'building' => 'required|string|max:100',
            'note' => 'nullable|string',
            'special_discount' => 'required|in:0,1',
            'payment_method' => 'required|in:cash,visa,coins',
            'store_id' => 'required|exists:stores,id',
            'coupon_code' => 'nullable|exists:coupons,code',
        ];
    }
}
