<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailConfirmPost extends FormRequest
{

    public function rules()
    {
        return [
            'id' => 'required|exists:users',
            'confirmation_code' => 'required|exists:users'
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'A id is required',
            'confirmation_code.required' => 'A confirmation_code is required',
            'id.exists' => 'The id is not exists',
            'confirmation_code.exists' => 'The confirmation_code is not exists'
        ];
    }
}
