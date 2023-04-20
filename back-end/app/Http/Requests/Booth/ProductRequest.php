<?php

namespace App\Http\Requests\Booth;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $rules = Product::$rules;
        if (request()->method() == 'PATCH') {
            $rules['img'] = 'nullable|image';
            $rules['gallery'] = 'nullable|min:1';
        }
        return $rules;
    }
}
