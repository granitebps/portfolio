<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CertificationRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'link' => 'required|string|url|max:255',
            'published' => 'required|string|max:255|date',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name is Required',
            'name.string' => 'Name is not valid',
            'name.max' => 'Name is too long. Max length is :max character',
            'institution.required' => 'Institution is Required',
            'institution.string' => 'Institution is not valid',
            'institution.max' => 'Institution is too long. Max length is :max character',
            'link.required' => 'Link is Required',
            'link.string' => 'Link is not valid',
            'link.max' => 'Link is too long. Max length is :max character',
            'link.url' => 'Link is not a valid URL',
            'published.required' => 'Published is Required',
            'published.string' => 'Published is not valid',
            'published.max' => 'Published is too long. Max length is :max character',
            'published.date' => 'Published is not a valid date',
        ];
    }
}
