<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ID 4: 商品一覧取得
     */
    public function test_商品一覧画面の仕様を網羅()
    {
        $me = User::factory()->create();
        $others = User::factory()->create();

        // 1. 他人の商品は表示される (user_id -> seller_id に修正)
        Item::factory()->create(['seller_id' => $others->id, 'name' => '他人の商品']);

        // 2. 自分が出品した商品は表示されない (user_id -> seller_id に修正)
        Item::factory()->create(['seller_id' => $me->id, 'name' => '自分の商品']);

        // 3. 購入済み商品は「Sold」と表示される (buyer_id をセットするだけでOK)
        Item::factory()->create([
            'seller_id' => $others->id,
            'name' => '売り切れ商品',
            'buyer_id' => $me->id  // これで isSold() が true になる
        ]);

        $response = $this->actingAs($me)->get('/');

        $response->assertStatus(200);
        $response->assertSee('他人の商品');
        $response->assertDontSee('自分の商品');
        $response->assertSee('Sold');
    }

    /**
     * ID 6: 商品検索機能
     */
    public function test_検索状態の保持を確認()
    {
        // 1. 準備：検索対象の商品を作成
        $item = Item::factory()->create(['name' => '検索ワード']);

        // 2. 「おすすめ」タブでの検索（search -> keyword に修正）
        $response = $this->get('/?keyword=検索');
        $response->assertStatus(200);
        $response->assertSee('検索ワード');

        // 3. 「マイリスト」タブでの検索（ここも keyword に修正）
        // ※お気に入り登録がなくても、入力欄に「検索」という文字が残っているか、
        // または検索クエリが維持されているかをチェックします。
        $responseMylist = $this->get('/?tab=mylist&keyword=検索');
        $responseMylist->assertStatus(200);

        // 商品自体は「お気に入り」していないので一覧には出ないかもしれませんが、
        // 検索窓（inputタグのvalue）に「検索」と残っているかを確認します。
        $responseMylist->assertSee('value="検索"', false);
    }

    /**
     * ID 7: 商品詳細情報取得
     */
    public function test_商品詳細画面の全項目表示()
    {
        $item = Item::factory()->create([
            'name' => 'こだわり商品',
            'brand' => 'ブランド名',
            'price' => 2000,
            'description' => '商品の説明文',
        ]);

        $categories = Category::factory()->count(2)->create();
        $item->categories()->attach($categories);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('こだわり商品');
        $response->assertSee('ブランド名');
        $response->assertSee('2,000');
        $response->assertSee('商品の説明文');
        foreach ($categories as $cat) {
            $response->assertSee($cat->name);
        }
    }

    /**
     * ID 9: コメント送信機能
     */
    public function test_コメント送信のバリデーションと制限()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $commentUrl = "/item/{$item->id}/comment";

        // 1. ログイン前は送信できない
        $this->post($commentUrl, ['comment' => 'テスト'])
            ->assertRedirect('/login');

        // 2. ログイン済みのユーザーは送信できる
        $this->actingAs($user)
            ->post($commentUrl, ['comment' => 'こんにちは'])
            ->assertStatus(302);

        // 3. バリデーション：空送信
        $this->actingAs($user)
            ->post($commentUrl, ['comment' => ''])
            ->assertSessionHasErrors(['comment' => 'コメントを入力してください']);

        // 4. バリデーション：255文字より多い (256文字)
        $this->actingAs($user)
            ->post($commentUrl, ['comment' => str_repeat('あ', 256)])
            ->assertSessionHasErrors(['comment']);
    }
}
