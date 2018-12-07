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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //update or create
//        $id = $this->file ? $this->file->getKey() : '';
        $rules = [
            'name' => 'required|max:255'
        ];
//        if($id){
//            $rules['id'] = 'required|exists';
//        }
        return $rules;
    }
}
