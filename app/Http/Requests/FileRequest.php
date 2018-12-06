<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //update or create
        $id = $this->file ? $this->file->getKey() : '';
        $rules = [
            'name' => 'required|max:80'
        ];
        if($id){
            $rules['file_id'] = 'required|exists';
        }
        return $rules;
    }
}
