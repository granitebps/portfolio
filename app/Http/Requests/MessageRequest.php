<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:255',
            'message' => 'required|string',
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
            'first_name.required' => 'First Name is required',
            'first_name.string' => 'First Name is not valid',
            'first_name.max' => 'First Name is too long. Max length is :max character',
            'last_name.required' => 'Last Name is required',
            'last_name.string' => 'Last Name is not valid',
            'last_name.max' => 'Last Name is too long. Max length is :max character',
            'email.required' => 'Email is required',
            'email.string' => 'Email is not valid',
            'email.max' => 'Email is too long. Max length is :max character',
            'email.string' => 'Email is not a valid email',
        ];
    }
}
