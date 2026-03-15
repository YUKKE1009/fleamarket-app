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
        return true;
    }

    /**
     * バリデーションルール (FN027)
     */
    public function rules()
    {
        return [
            // 全てを img_url と temp_img_url に統一
            'img_url'      => 'nullable|image|mimes:jpeg,png',
            'temp_img_url' => 'nullable|string',
            'name'         => 'required|string|max:20',
            'post_code'    => ['required', 'string', 'regex:/^\d{3}-\d{4}$/'],
            'address'      => 'required|string',
            'building'     => 'nullable|string',
        ];
    }

    /**
     * エラーメッセージの日本語化
     */
    public function messages()
    {
        return [
            // 修正：キー名を rules と合わせる (img_url)
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
     * バリデーション前に実行する処理
     */
    protected function prepareForValidation()
    {
        if ($this->hasFile('img_url')) {
            $path = $this->file('img_url')->store('tmp', 'public');

            // これが最強です：リクエストの生データとして temp_img_url を追加します
            $this->merge(['temp_img_url' => $path]);
            $this->offsetSet('temp_img_url', $path);

            // 念のためセッションにも直接書く
            session(['temp_img_url' => $path]);
        }
    }
}
