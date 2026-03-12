<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Http\Requests\LoginRequest;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;

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
        // Fortifyの内部で使われるLoginRequestを、自作のLoginRequestに差し替え
        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);

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

        // 会員登録が完了した直後のリダイレクト先を「プロフィール編集画面」に指定
        $this->app->instance(
            \Laravel\Fortify\Contracts\RegisterResponse::class,
            new class implements \Laravel\Fortify\Contracts\RegisterResponse {
                public function toResponse($request)
                {
                    return redirect('/mypage/profile');
                }
            }
        );
    }
    
}
