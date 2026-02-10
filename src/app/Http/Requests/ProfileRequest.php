<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを行う権限があるか確認
     */
    public function authorize()
    {
        return true; // 忘れずに true に
    }

    /**
     * バリデーションルール (FN027)
     */
    public function rules()
    {
        return [
            'image_url'   => 'nullable|image|mimes:jpeg,png', // 拡張子指定
            'name'      => 'required|string|max:20',        // 20文字以内
            'post_code' => ['required', 'string', 'regex:/^\d{3}-\d{4}$/'], // ハイフンあり8文字
            
            'address'   => 'required|string',               // 入力必須
            'building'  => 'nullable|string',
        ];
    }

    /**
     * エラーメッセージの日本語化
     */
    public function messages()
    {
        return [
            'img_url.image'      => '指定されたファイルが画像ではありません。',
            'img_url.mimes'      => '画像の形式はjpegまたはpng形式でアップロードしてください。',
            'name.required'      => 'お名前を入力してください。',
            'name.max'           => 'お名前は20文字以内で入力してください。',
            'post_code.required' => '郵便番号を入力してください。',
            'post_code.regex'    => '郵便番号はハイフンありの8文字で入力してください。',
            'address.required'   => '住所を入力してください。',
        ];
    }
} 