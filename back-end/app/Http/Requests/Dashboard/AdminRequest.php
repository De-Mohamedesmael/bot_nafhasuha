<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
        $rules = Admin::$rules;

        if (request()->method == 'PATCH') {
            $rules['password'] = 'nullable|min:6|max:225';
            $rules['email'] = $rules['email'] . ',email,' . $this->route('admin');
        }

        return $rules;
    }
}
