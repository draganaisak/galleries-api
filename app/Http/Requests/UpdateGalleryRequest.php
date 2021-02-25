<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGalleryRequest extends FormRequest
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
            'name' => 'sometimes|string|min:2|max:255',
            'description' => 'string|max:1000',
            'images' => 'sometimes|array|min:1',
            'images.*.url' => 'sometimes|URL|ends_with:.png,.jpeg,.jpg'
        ];
    }
}
