<?php

namespace App\Http\Requests\Booth;

use App\Models\Section;
use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
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
            'name' => 'required|max:200',
            'section' => 'nullable|exists:sections,id,store_id,' . auth('booth')->id(),
        ];
    }
}
