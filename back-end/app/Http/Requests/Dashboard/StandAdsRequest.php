<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Ads;
use Illuminate\Foundation\Http\FormRequest;

class StandAdsRequest extends FormRequest
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
        //mimetypes:image/jpeg,image/jpg,image/png,image/gif
        $rules = Ads::$rules;

        $route = $this->route('standAd') ? $this->route('standAd') : 'null';
        $rules['sort'] .= '|unique:ads,sort,' . $route . ',id,name,stand,floor,' . request('floor') . ',hall,' . request('hall');

        $rules['front_files'] = 'required|array|min:1';
        $rules['back_files'] = 'required|array|min:1';
        $rules['front_files.*'] = 'required|image';
        $rules['back_files.*'] = 'required|image';

        if (request()->method == 'PATCH') {
            $rules['back_files'] = str_replace('required', 'nullable', $rules['back_files']);
            $rules['front_files'] = str_replace('required', 'nullable', $rules['front_files']);
        }

        return $rules;
    }
}
