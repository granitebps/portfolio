<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SkillRequest extends FormRequest
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
            'percentage' => 'required|numeric|min:0|max:100'
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
            'percentage.required' => 'Percentage is required',
            'percentage.numeric' => 'Percentage is not valid',
            'percentage.min' => 'Percentage is too small. Min number is :min',
            'percentage.max' => 'Percentage is too big. Max number is :max',
        ];
    }
}
