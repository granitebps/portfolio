<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExperienceRequest extends FormRequest
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
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'desc' => 'required|string',
            'current_job' => 'sometimes|nullable|boolean',
            'start_date' => 'required|string|max:255|date',
            'end_date' => 'exclude_if:current_job,1|required|string|max:255|date'
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
            'company.required' => 'Company is required',
            'company.string' => 'Company is not valid',
            'company.max' => 'Company is too long. Max length is :max character',
            'position.required' => 'Position is required',
            'position.string' => 'Position is not valid',
            'position.max' => 'Position is too long. Max length is :max character',
            'desc.required' => 'Description is required',
            'desc.string' => 'Description is not valid',
            'current_job.boolean' => 'Current Job is not valid',
            'start_date.required' => 'Start Date is required',
            'start_date.string' => 'Start Date is not valid',
            'start_date.max' => 'Start Date is too long. Max length is :max character',
            'start_date.date' => 'Start Date is not valid',
            'end_date.required' => 'End Date is required',
            'end_date.string' => 'End Date is not valid',
            'end_date.max' => 'End Date is too long. Max length is :max character',
            'end_date.date' => 'End Date is not valid',
        ];
    }
}
