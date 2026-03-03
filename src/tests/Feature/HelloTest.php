<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase; // これを追加
use Tests\TestCase;
use App\Models\User; // これを追加

class HelloTest extends TestCase
{
    use RefreshDatabase; // これを追加（テストが終わるたびにDBを綺麗にする）

    public function testHello()
    {
        // 1. 基本のテスト（テキストの復習）
        $txt = "Hello World";
        $this->assertEquals('Hello World', $txt);

        // 2. データベースのテスト
        // テスト用DBにユーザーを一人作ってみる
        User::factory()->create([
            'name' => 'aaa',
            'email' => 'bbb@ccc.com',
            'password' => bcrypt('test12345'),
        ]);

        // 作ったユーザーが本当にDBにいるかチェック
        $this->assertDatabaseHas('users', [
            'name' => 'aaa',
            'email' => 'bbb@ccc.com',
        ]);
    }
}