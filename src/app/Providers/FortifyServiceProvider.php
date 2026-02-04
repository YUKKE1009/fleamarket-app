<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // ログイン画面 (PG04) を表示する設定
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // 会員登録画面 (PG03) を表示する設定
        Fortify::registerView(function () {
            return view('auth.register');
        });
    }
}
