<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Store;
use Illuminate\Foundation\Http\FormRequest;

class BoothRequest extends FormRequest
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
            'name:ar' => 'required|max:200',
            'name:en' => 'required|max:200',
            'email' => 'required|max:200|email|unique:stores',
            'password' => 'required|min:6',
            'logo' => 'required|image',
            'floor' => 'required|int|in:1,2,3',
            'hall' => 'required|int|min:1|max:10',
            'category_id' => 'required|exists:categories,id',
            'banner' => 'required|image',
            'banner_left' => 'required|image',
            'banner_right' => 'required|image',
        ];
    }
}
