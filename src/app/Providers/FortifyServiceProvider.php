<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 会員登録の実行ロジック (P-01) を紐付け
        Fortify::createUsersUsing(CreateNewUser::class);

        // 会員登録画面 (PG03) の表示設定
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // ログイン画面 (PG04) の表示設定
        Fortify::loginView(function () {
            return view('auth.login');
        });
    }
}
