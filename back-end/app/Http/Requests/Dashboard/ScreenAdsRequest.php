<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Ads;
use Illuminate\Foundation\Http\FormRequest;

class ScreenAdsRequest extends FormRequest
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

        $route = $this->route('screenAd') ? $this->route('screenAd') : 'null';
        $rules['sort'] .= '|unique:ads,sort,' . $route . ',id,name,screen,floor,' . request('floor') . ',hall,' . request('hall');

        $rules['slider'] = 'required|array|min:1';
        $rules['slider.*'] = 'required|image';

        if (request()->method == 'PATCH') {
            $rules['slider'] = str_replace('required', 'nullable', $rules['slider']);
        }

        return $rules;
    }
}
