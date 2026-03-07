<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;

class ExhibitionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ID 15: 商品出品情報の網羅テスト
     */
    public function test_商品出品画面にて必要な全情報が保存できること()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $category = Category::factory()->create(['name' => 'ファッション']);
        $condition = Condition::factory()->create(['name' => '良好']);

        Storage::fake('public');
        $file = UploadedFile::fake()->create('test_item.jpg', 100);

        // コントローラー(ExhibitionController)の store メソッドの引数名に合わせる
        $postData = [
            'name'         => 'テスト商品名',
            'brand'        => 'テストブランド',
            'description'  => 'テスト用の商品の説明です。',
            'price'        => 5000,
            'category_ids' => [$category->id], // category_id ではなく category_ids
            'condition_id' => $condition->id,
            'image_url'    => $file,           // image ではなく image_url
        ];

        // 実行
        $response = $this->actingAs($user)->post('/sell', $postData);

        // 検証
        $response->assertStatus(302);

        $this->assertDatabaseHas('items', [
            'name'         => 'テスト商品名',
            'brand'        => 'テストブランド',
            'price'        => 5000,
            'condition_id' => $condition->id,
        ]);

        // カテゴリーが正しく紐づいているかも確認（ID 15 網羅）
        $item = \App\Models\Item::where('name', 'テスト商品名')->first();
        $this->assertTrue($item->categories->contains($category->id));
    }
}
