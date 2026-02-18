<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを行う権限があるかどうかを判断
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルールの定義
     */
    public function rules(): array
    {
        return [
            // 郵便番号：必須、ハイフンありの8文字 (例: 123-4567)
            'post_code' => ['required', 'string', 'size:8', 'regex:/^\d{3}-\d{4}$/'],
            // 住所：必須
            'address'  => ['required', 'string', 'max:255'],
            // 建物名：任意 (必要なら max:255 などを追加)
            'building' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * エラーメッセージのカスタマイズ
     */
    public function messages(): array
    {
        return [
            'ppost_code.required' => '郵便番号を入力してください',
            'post_code.size'     => '郵便番号はハイフンを含めて8文字で入力してください',
            'post_code.regex'    => '郵便番号は 000-0000 の形式で入力してください',
            'address.required'  => '住所を入力してください',
        ];
    }
}
