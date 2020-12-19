<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TechnologyRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
        ];
        if ($this->method() === 'POST') {
            $rules['pic'] = 'required|image|max:2048';
        } else if ($this->method() === 'PUT') {
            $rules['pic'] = 'sometimes|nullable|image|max:2048';
        }
        return $rules;
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
            'pic.required' => 'Image is required',
            'pic.image' => 'Image is not valid',
            'pic.max' => 'Image is too big. Max size is :max kilobytes',
        ];
    }
}
