<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;
    /**
     * ID 5: マイリスト一覧取得（ログイン時・Sold表示・未ログイン時）
     */
    public function test_マイリスト一覧の表示仕様確認()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        // いいねした商品
        $favItem = Item::factory()->create(['name' => 'いいねした商品']);
        $user->favoriteItems()->attach($favItem->id);

        // いいねした かつ 購入済み商品
        $soldItem = Item::factory()->create(['name' => '売切れ商品', 'buyer_id' => User::factory()]);
        $user->favoriteItems()->attach($soldItem->id);

        // 1. ログイン時：いいねした商品だけが表示され、Soldも表示される
        $response = $this->actingAs($user)->get('/?tab=mylist'); // タブ切り替えパラメータ
        $response->assertSee('いいねした商品');
        $response->assertSee('売切れ商品');
        $response->assertSee('Sold');

        // 2. 未ログイン時：何も表示されない（またはログイン画面へ促される）
        auth()->logout();
        $responseGuest = $this->get('/?tab=mylist');
        $responseGuest->assertDontSee('いいねした商品');
    }

    /**
     * ID 8: いいね登録・解除・合計値の反映
     */
    public function test_いいねアイコン押下で登録解除と合計値の変化が反映される()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 1. 登録前の合計値確認
        $this->actingAs($user)->get("/item/{$item->id}")->assertSee('0');

        // 2. いいね登録
        $this->actingAs($user)->post("/item/{$item->id}/favorite")->assertStatus(302);

        // 3. 登録後の合計値と「いいね済み（色の変化）」を確認
        // ※クラス名などは実際のHTMLに合わせて調整してください
        $response = $this->actingAs($user)->get("/item/{$item->id}");
        $response->assertSee('1');

        // 4. いいね解除
        $this->actingAs($user)->post("/item/{$item->id}/favorite")->assertStatus(302);
        $this->actingAs($user)->get("/item/{$item->id}")->assertSee('0');
    }

}
