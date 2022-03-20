<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'name'      => 'required|max:100',
            'phone'     => 'required|max:20',
            'email'     => 'required|max:100|email',
            'address'   => 'required',
            'status'    => 'nullable|in:active,freeze'
        ];
    }
}