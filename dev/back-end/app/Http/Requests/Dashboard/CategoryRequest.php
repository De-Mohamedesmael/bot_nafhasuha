<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $rules = [
            'name' => 'required|max:200',
            'parent_id' => 'required|exists:categories,id'
        ];
        if (request()->method == 'PATCH' && $this->route('category') <= 15) {
            $rules['parent_id'] = 'nullable';
        }
        return $rules;
    }
}
