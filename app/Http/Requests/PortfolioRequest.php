<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PortfolioRequest extends FormRequest
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
            'desc' => 'required|string',
            'type' => 'required|numeric|in:1,2',
            'url' => 'sometimes|nullable|string|max:255|url',
        ];
        if ($this->method() === 'POST') {
            $rules['thumbnail'] = 'required|image|max:2048';
            $rules['pic'] = 'required';
            $rules['pic.*'] = 'image|max:2048';
        } else if ($this->method() === 'PUT') {
            $rules['thumbnail'] = 'sometimes|nullable|image|max:2048';
            $rules['pic.*'] = 'sometimes|nullable|image|max:2048';
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
            'desc.required' => 'Description is required',
            'desc.string' => 'Description is not valid',
            'type.required' => 'Type is required',
            'type.numeric' => 'Type is not valid',
            'type.in' => 'Type is not valid',
            'url.string' => 'URL is not valid',
            'url.max' => 'URL is too long. Max length is :max character',
            'url.url' => 'URL is not a valid URL',
            'thumbnail.required' => 'Thumbnail is required',
            'thumbnail.image' => 'Thumbnail is not valid',
            'thumbnail.max' => 'Thumbnail is too big. Max size is :max kilobytes',
            'pic.required' => 'Image is required',
            'pic.*.image' => 'Image is not valid',
            'pic.*.max' => 'Image is too big. Max size is :max kilobytes',
        ];
    }
}
