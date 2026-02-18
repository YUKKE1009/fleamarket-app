<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 忘れずにtrueに！
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string'],
            'description'  => ['required', 'string', 'max:255'],
            'image_url'    => ['required', 'image', 'mimes:jpeg,png'],
            'category_ids' => ['required', 'array', 'min:1'],
            'condition_id' => ['required'],
            'price'        => ['required', 'integer', 'min:0'],
            'brand'        =>['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => '商品名を入力してください',
            'description.required'  => '商品説明を入力してください',
            'description.max'       => '商品説明は255文字以内で入力してください',
            'image_url.required'    => '商品画像をアップロードしてください',
            'image_url.mimes'       => '指定された拡張子（.jpeg, .png）を選択してください',
            'category_ids.required' => 'カテゴリーを選択してください',
            'category_ids.array'    => 'カテゴリーの形式が正しくありません',
            'category_ids.min'      => 'カテゴリーを1つ以上選択してください',
            'condition_id.required' => '商品の状態を選択してください',
            'price.required'        => '商品価格を入力してください',
            'price.integer'         => '商品価格は数値で入力してください',
            'price.min'             => '商品価格は0円以上で入力してください',
        ];
    }
}
