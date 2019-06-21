<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetectImageRequest extends FormRequest
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
            'filepath' => [
                'required',
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
                // check file exists
                if (false == @file_get_contents($this->input('filepath'), NULL, NULL, 0, 1)){
                    $validator->errors()->add('filepath', '画像ファイルが存在しません。');
                }
            }
        });
    }
}
