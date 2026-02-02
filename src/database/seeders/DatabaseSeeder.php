<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. ユーザー作成
        \App\Models\User::factory(1)->create();

        // 2. カテゴリー作成（UI画像に基づいたリストを登録）
        $this->call(CategorySeeder::class);

        // 3. コンディション作成（良好、目立った傷なしなど）
        $this->call(ConditionSeeder::class);

        // 4. 商品作成（上記2つが揃った状態で実行）
        $this->call(ItemsTableSeeder::class);
    }
}
