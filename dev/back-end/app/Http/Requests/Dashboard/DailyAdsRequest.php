<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Ads;
use Illuminate\Foundation\Http\FormRequest;

class DailyAdsRequest extends FormRequest
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

        $route = $this->route('dailyAd') ? $this->route('dailyAd') : 'null';
        $rules['hall'] .= '|unique:ads,hall,' . $route . ',id,name,daily,floor,' . request('floor');

        $rules['store_id'] = 'nullable|exists:stores,id';
        $rules['image'] = 'required|image';

        if (request()->method == 'PATCH') {
            $rules['image'] = str_replace('required', 'nullable', $rules['image']);
        }

        return $rules;
    }
}
