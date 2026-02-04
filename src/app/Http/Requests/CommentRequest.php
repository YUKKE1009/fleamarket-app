<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを行う権限があるかどうかを判断
     */
    public function authorize()
    {
        return true;
    }

    /**
     * リクエストに適用するバリデーションルール
     */
    public function rules()
    {
        return [
            // 入力必須(required)、文字列(string)、最大255文字(max:255)
            'comment' => 'required|string|max:255',
        ];
    }

    /**
     * エラーメッセージのカスタマイズ
     */
    public function messages()
    {
        return [
            'comment.required' => 'コメントを入力してください',
            'comment.max' => '255文字以内で入力してください',
        ];
    }
}
