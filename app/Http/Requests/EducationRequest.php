<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EducationRequest extends FormRequest
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
            'institute' => 'required|string|max:255',
            'start_year' => 'required|integer|min:1900|max:9999|date_format:Y',
            'end_year' => 'required|integer|min:1900|max:9999|date_format:Y'
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
            'institute.required' => 'Institute is required',
            'institute.string' => 'Institute is not valid',
            'institute.max' => 'Institute is too long. Max length is :max character',
            'start_year.required' => 'Start Year is required',
            'start_year.integer' => 'Start Year is not valid',
            'start_year.min' => 'Minimal Start Year is :min',
            'start_year.max' => 'Maximal Start Year is :max',
            'start_year.date_format' => 'Start Year is not valid',
            'end_year.required' => 'End Year is required',
            'end_year.integer' => 'End Year is not valid',
            'end_year.min' => 'Minimal End Year is :min',
            'end_year.max' => 'Maximal End Year is :max',
            'end_year.date_format' => 'End Year is not valid',
        ];
    }
}
