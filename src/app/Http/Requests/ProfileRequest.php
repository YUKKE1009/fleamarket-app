<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * バリデーションルール
     */
    public function rules()
    {
        return [
            // temp_profile_img に統一
            'img_url'           => 'nullable|image|mimes:jpeg,png',
            'temp_profile_img'  => 'nullable|string',
            'name'              => 'required|string|max:20',
            'post_code'         => ['required', 'string', 'regex:/^\d{3}-\d{4}$/'],
            'address'           => 'required|string',
            'building'          => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'img_url.image'      => '指定されたファイルが画像ではありません。',
            'img_url.mimes'      => '画像の形式はjpegまたはpng形式でアップロードしてください。',
            'name.required'      => 'ユーザー名を入力してください。',
            'name.max'           => 'ユーザー名は20文字以内で入力してください。',
            'post_code.required' => '郵便番号を入力してください。',
            'post_code.regex'    => '郵便番号はハイフンありの8文字で入力してください。',
            'address.required'   => '住所を入力してください。',
        ];
    }

    /**
     * バリデーションエラー時に実行
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
