<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
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
            'file' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png,gif',
                //'max:200', //200KB
            ],
        ];
    }
    
    /**
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (false == $validator->failed()) {
                // request is valid.
                if (false == $this->file('file')->isValid()) {
                    $validator->errors()->add('file', '画像がアップロードされていないか不正なデータです。');
                }
            }
        });
    }
}
