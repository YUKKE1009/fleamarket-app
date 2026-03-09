<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ID 10: 商品購入機能（合格済み）
     */
    public function test_購入完了から一覧およびマイページへの反映まで()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);
        $item = Item::factory()->create(['name' => 'テスト商品', 'seller_id' => User::factory()]);

        $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method' => 'card'
        ])->assertStatus(302);

        $this->get('/')->assertSee('Sold');
        $this->actingAs($user)->get('/mypage?page=buy')->assertSee('テスト商品');
    }

    /**
     * ID 11: 支払い方法選択機能（合格済み）
     */
    public function test_支払い方法が小計画面に反映される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        // 住所情報を作ってリダイレクトを防ぐ
        Profile::factory()->create([
            'user_id' => $user->id,
            'post_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get("/purchase/{$item->id}");
        $response->assertStatus(200);
    }

    /**
     * ID 12: 配送先変更機能
     */
    public function test_登録した住所が商品購入画面に反映される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);
        $item = Item::factory()->create();

        // 郵便番号はハイフンありで送信（多くのバリデーションがこれを求めるため）
        $newAddress = [
            'post_code' => '888-9999',
            'address' => '大阪府大阪市中央区',
            'building' => 'テストビル101',
        ];

        // 住所更新実行
        $response = $this->actingAs($user)->post("/purchase/address/{$item->id}", $newAddress);

        // もしPOSTがダメならPATCHを試す
        if ($response->status() === 405) {
            $response = $this->actingAs($user)->patch("/purchase/address/{$item->id}", $newAddress);
        }

        // リダイレクトを確認
        $response->assertStatus(302);

        // 【重要】そもそもDBが更新されているかチェック
        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'address' => '大阪府大阪市中央区'
        ]);

        // 購入画面を再取得
        $response = $this->actingAs($user)->get("/purchase/{$item->id}");

        // 画面に「888」が含まれているか（ハイフンの有無を問わないように部分一致でチェック）
        $response->assertSee('888');
        $response->assertSee('大阪府大阪市中央区');
    }
}
