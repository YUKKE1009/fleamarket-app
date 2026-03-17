<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ID 1: 会員登録機能のバリデーションと登録処理
     */
    public function test_会員登録バリデーションと登録成功の検証()
    {
        // 1. 各項目のバリデーションを一気に検証
        // 名前未入力
        $this->post('/register', ['name' => '', 'email' => 't@e.com', 'password' => '12345678', 'password_confirmation' => '12345678'])
            ->assertSessionHasErrors(['name' => 'お名前を入力してください']);

        // メール未入力
        $this->post('/register', ['name' => 'a', 'email' => '', 'password' => '12345678', 'password_confirmation' => '12345678'])
            ->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);

        // パスワード未入力
        $this->post('/register', ['name' => 'a', 'email' => 'a@e.com', 'password' => '', 'password_confirmation' => ''])
            ->assertSessionHasErrors(['password' => 'パスワードを入力してください']);

        // パスワード7文字以下
        $this->post('/register', ['name' => 'a', 'email' => 'a@e.com', 'password' => '1234567', 'password_confirmation' => '1234567'])
            ->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);

        // パスワード不一致
        $this->post('/register', ['name' => 'a', 'email' => 'a@e.com', 'password' => '12345678', 'password_confirmation' => 'diff'])
            ->assertSessionHasErrors(['password' => 'パスワードと一致しません']);

        // 2. 全項目入力時の登録成功
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
        $response->assertRedirect('/mypage/profile');
    }

    /**
     * ID 2: ログイン機能のバリデーションとログイン成功
     */
    public function test_ログインバリデーションと成功の検証()
    {
        // 1. バリデーション
        $this->post('/login', ['email' => '', 'password' => 'password'])
            ->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);

        $this->post('/login', ['email' => 'test@e.com', 'password' => ''])
            ->assertSessionHasErrors(['password' => 'パスワードを入力してください']);

        $this->post('/login', ['email' => 'none@e.com', 'password' => 'wrong'])
            ->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);

        // 2. ログイン成功
        $user = User::factory()->create(['password' => bcrypt($password = 'password123')]);
        $response = $this->post('/login', ['email' => $user->email, 'password' => $password]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }

    /**
     * ID 3: ログアウト機能
     */
    public function test_ログアウトができる()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    /**
     * ID 16: メール認証機能
     */
    public function test_メール認証が必要な場合に認証メールが送信される()
    {
        // 1. メールの送信をフェイク（実際には送らない）
        \Illuminate\Support\Facades\Notification::fake();

        // 2. 未認証のユーザーとして登録処理を実行
        $this->post('/register', [
            'name' => '認証テスト',
            'email' => 'verify@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 3. ユーザーが作成されているか確認
        $user = User::where('email', 'verify@example.com')->first();
        $this->assertNotNull($user);

        // 4. 認証メール（通知）がそのユーザーに送られたか確認
        // ※Laravel標準やFortifyの場合、VerifyEmail通知が使われます
        \Illuminate\Support\Facades\Notification::assertSentTo(
            $user,
            \Illuminate\Auth\Notifications\VerifyEmail::class
        );
    }

    public function test_未認証ユーザーはプロフィール画面にアクセスできず認証誘導画面へ飛ぶ()
    {
        // ここで factory の unverified() を呼び出します
        // これにより email_verified_at が null のユーザーが作成されます
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/mypage/profile');

        // 認証待ち画面へ飛ばされることを期待
        $response->assertRedirect('/email/verify');
    }

    /**
     * ID 16: メール認証サイトのメール認証を完了すると、プロフィール設定画面に遷移する
     */
    public function test_メール認証リンクを押下すると認証が完了しプロフィール画面に遷移する()
    {
        $user = User::factory()->unverified()->create();

        // Laravel標準のメール認証URLを生成
        $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())]
        );

        // 生成したURLにアクセス（メールの「認証はこちらから」ボタンを押したことに相当）
        $response = $this->actingAs($user)->get($verificationUrl);

        // 1. プロフィール設定画面にリダイレクトされるか
        $response->assertRedirect('/mypage/profile');

        // 2. 実際にDB上でメール確認済み（email_verified_atが埋まったか）を確認
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}
