<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ];
        if ($this->method() === 'POST') {
            $rules['image'] = 'required|image|max:2048';
        } else if ($this->method() === 'PUT') {
            $rules['image'] = 'sometimes|image|max:2048';
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
            'title.required' => 'Title is Required',
            'title.string' => 'Title is not valid',
            'title.max' => 'Title is too long. Max length is :max character',
            'body.required' => 'Body is required',
            'body.string' => 'Body is not valid',
            'image.required' => 'Image is required',
            'image.image' => 'Image is not valid',
            'image.max' => 'Image is too big. Max size is :max kilobytes',
        ];
    }
}
