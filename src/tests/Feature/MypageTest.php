<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;

class MyPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ID 13: プロフィール情報の取得
     */
    public function test_プロフィール設定画面に初期値が表示される()
    {
        $user = User::factory()->create(['name' => 'テスト太郎']);
        Profile::factory()->create([
            'user_id' => $user->id,
            'post_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テックビル'
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('テスト太郎');
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区');
        $response->assertSee('テックビル');
    }

    /**
     * ID 14: プロフィール更新機能
     */
    public function test_プロフィール更新ができる()
    {
        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);

        $updateData = [
            'name' => '新しい名前',
            'post_code' => '000-0000',
            'address' => '大阪府大阪市',
            'building' => '新しいビル',
        ];

        // 405エラー対策: アプリのルート設定に合わせて patch または post を切り替えます
        // もしダメならここを patch に書き換えてみてください
        $response = $this->actingAs($user)->post('/mypage/profile', $updateData);

        if ($response->status() === 405) {
            $response = $this->actingAs($user)->patch('/mypage/profile', $updateData);
        }

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', ['name' => '新しい名前']);
        $this->assertDatabaseHas('profiles', ['address' => '大阪府大阪市']);
    }

    /**
     * マイページ：出品した商品と購入した商品の表示
     */
    public function test_マイページで自分に関連する商品が表示される()
    {
        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);

        // 自分が「出品」した商品 (seller_id)
        Item::factory()->create(['seller_id' => $user->id, 'name' => '私が出品した商品']);

        // 自分が「購入」した商品 (buyer_id)
        Item::factory()->create(['buyer_id' => $user->id, 'name' => '私が買った商品']);

        // 1. デフォルト（出品した商品）の確認
        $response = $this->actingAs($user)->get('/mypage');
        $response->assertStatus(200);
        $response->assertSee('私が出品した商品');

        // 2. 購入した商品タブの確認 (tab=buy を page=buy に修正)
        $responseBuy = $this->actingAs($user)->get('/mypage?page=buy');
        $responseBuy->assertStatus(200);
        $responseBuy->assertSee('私が買った商品');
    }
}
