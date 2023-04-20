<?php

namespace App\Http\Requests\Store;

use App\Models\Store;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        $rules = Store::$rules;
        $rules['logo'] = 'nullable|image';
        $rules['category_id'] = 'nullable';
        $rules['banner'] = 'nullable|image';
        $rules['password'] = 'nullable|min:6';
        $rules['email'] = $rules['email'] . ',email,' . auth('store')->id();
        return $rules;
    }
}
