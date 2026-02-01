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

        // 2. カテゴリ作成（とりあえず1つ）
        \App\Models\Category::create(['content' => '未分類']);

        // 3. コンディション作成（★これが先！）
        $this->call(ConditionSeeder::class);

        // 4. 商品作成
        $this->call(ItemsTableSeeder::class);
    }
}
