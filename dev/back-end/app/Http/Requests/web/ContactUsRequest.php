<?php

namespace App\Http\Requests\web;

use App\Models\Store;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ContactUsRequest extends FormRequest
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
            'contact-name' => 'required|max:200',
            'contact-phone' => 'required|max:20',
//            'contact-email' => 'required|max:200|email',
//            'subject' => 'required|min:6|max:30',
            'contact-message' => 'required|string',
        ];
    }

    /**
     * Filter the input data to remove HTML tags from text fields
     *
     * @param array|null $keys
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all($keys);
//        if(){
//
//        }
//        $data['contact-message'] = preg_replace('/<[^>]*>/', '', $data['contact-message']);
//        $data['contact-phone'] = preg_replace('/[^0-9]/', '', $data['contact-phone']);
//        $data['contact-name'] = preg_replace('/[^a-zA-Z0-9_ -]/', '', $data['contact-name']);
//        $data['subject'] = preg_replace('/[^a-zA-Z0-9_ -]/', '', $data['subject']);
        return $data;
    }

}
