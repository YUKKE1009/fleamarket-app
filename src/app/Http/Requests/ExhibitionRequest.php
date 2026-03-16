<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ExhibitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string'],
            'description'  => ['required', 'string', 'max:255'],
            // temp_img_urlがあればimage_urlは必須にしない
            'image_url'    => [$this->filled('temp_img_url') ? 'nullable' : 'required', 'image', 'mimes:jpeg,png'],
            'temp_img_url' => ['nullable', 'string'],
            'category_ids' => ['required', 'array', 'min:1'],
            'condition_id' => ['required'],
            'price'        => ['required', 'integer', 'min:0'],
        ];
    }

    // ★重要：エラーで戻る「直前」に、強制的に画像を保存してセッションに叩き込む
    protected function failedValidation(Validator $validator)
    {
        if ($this->hasFile('image_url')) {
            // 画像を保存してパスを取得
            $path = $this->file('image_url')->store('tmp', 'public');
            // リクエストデータにパスを合流させる
            $this->merge(['temp_img_url' => $path]);
        }

        // 入力値をすべてセッションに保存してエラー画面に戻る
        session()->put('_old_input', $this->all());

        parent::failedValidation($validator);
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
