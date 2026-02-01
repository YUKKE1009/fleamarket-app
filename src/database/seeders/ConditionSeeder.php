<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conditions = [
            ['content' => '良好'],
            ['content' => '目立った傷や汚れなし'],
            ['content' => 'やや傷や汚れあり'],
            ['content' => '状態が悪い'],
        ];
        foreach ($conditions as $condition) {
            \App\Models\Condition::create($condition);
        }
    }
}
