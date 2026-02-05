<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    protected function passwordRules(): array
    {
        // 簡易的に 'confirmed' (確認用パスワード一致) だけチェック
        return ['required', 'string', new Password, 'confirmed'];
    }
}
