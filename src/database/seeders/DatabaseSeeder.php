<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. まずマスターデータ（カテゴリと状態）を作成
        $this->call([
            CategorySeeder::class,
            ConditionSeeder::class,
        ]);

        // 2. 次にユーザー（admin）を作成 ★ここが重要！
        // 商品(Items)を作る前に、出品者となるユーザーが存在していなければなりません
        $admin = User::create([
            'id' => 1, // 明示的にIDを1に指定
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // 3. ユーザーができたので、商品を作成
        $this->call(ItemsTableSeeder::class);

        // 4. 商品ができたので、コメントを作成
        $items = Item::all();
        foreach ($items as $item) {
            Comment::create([
                'user_id' => $admin->id,
                'item_id' => $item->id,
                'comment' => 'こちらにコメントが入ります。',
            ]);
        }
    }
}
