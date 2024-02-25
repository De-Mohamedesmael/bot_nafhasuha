<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;

class CoinRequest extends FormRequest
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
            'coins' => 'required|int|min:1',
            'users' => 'required|array|min:1',
            'users.*' => 'required|exists:users,id',
        ];
    }
}
