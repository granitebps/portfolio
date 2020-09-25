<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
            'icon' => 'required|string|max:255',
            'desc' => 'required|string',
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
            'name.required' => 'Name is required',
            'name.string' => 'Name is not valid',
            'name.max' => 'Name is too long. Max length is :max character',
            'icon.required' => 'Icon is required',
            'icon.string' => 'Icon is not valid',
            'icon.max' => 'Icon is too long. Max length is :max character',
            'desc.required' => 'Description is required',
            'desc.string' => 'Description is not valid',
        ];
    }
}
