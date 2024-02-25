<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Store;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'id' => 'required|int|min:1|max:330|unique:stores',
            'name:ar' => 'required|max:200',
            'name:en' => 'required|max:200',
            'email' => 'required|max:200|email|unique:stores',
            'password' => 'required|min:6',
            'logo' => 'required|image',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
