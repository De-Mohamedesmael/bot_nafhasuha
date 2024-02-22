<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Store;
use Illuminate\Foundation\Http\FormRequest;

class TrashedStoreRequest extends FormRequest
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
            'is_active' => 'required|in:0,1',
            'can_login' => 'required|in:0,1',
        ];
    }
}
