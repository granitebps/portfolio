<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:255'
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
            'username.required' => 'Username is required',
            'password.required' => 'Password is required',
            'username.string' => 'Username is not valid',
            'password.string' => 'Password is not valid',
            'username.max' => 'Username is too long. Max length is 255 character',
            'password.max' => 'Password is too long. Max length is 255 character',
            'password.min' => 'Password is too short. Min length is 8 character',
        ];
    }
}
