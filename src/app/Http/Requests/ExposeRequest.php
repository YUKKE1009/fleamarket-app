<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExposeRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを行う権限があるか確認
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルール(FN028)
     */
    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:255',
            'description'  => 'required|string|max:1000',
            'price'        => 'required|integer|min:0|max:9999999',
            'condition_id' => 'required|exists:conditions,id',
            'category_ids' => 'required|array|min:1', // 最低1つ選択
            'image'        => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'brand'        => 'nullable|string|max:255',
        ];
    }

    /**
     * エラーメッセージの日本語化（任意）
     */
    public function messages(): array
    {
        return [
            'name.required'         => '商品名を入力してください',
            'description.required'  => '商品の説明を入力してください',
            'price.required'        => '販売価格を入力してください',
            'price.min'             => '0円以上で入力してください',
            'price.max'             => '9,999,999円以下で入力してください',
            'condition_id.required' => '商品の状態を選択してください',
            'category_ids.required' => 'カテゴリーを1つ以上選択してください',
            'image.required'        => '商品画像をアップロードしてください',
            'image.image'           => '指定されたファイルが画像ではありません',
        ];
    }
}