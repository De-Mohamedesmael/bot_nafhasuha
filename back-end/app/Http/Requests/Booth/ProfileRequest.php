<?php

namespace App\Http\Requests\Booth;

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
        $rules['welcome_message:en'] = 'nullable';
        $rules['welcome_message:ar'] = 'nullable';
        $rules['banner'] = 'nullable|image';
        $rules['banner_right'] = 'nullable|image';
        $rules['banner_left'] = 'nullable|image';
        $rules['password'] = 'nullable|min:6';
        $rules['email'] = $rules['email'] . ',email,' . auth('booth')->id();
        return $rules;
    }
}
