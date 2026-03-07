<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'seller_id'    => User::factory(),
            'condition_id' => Condition::factory(),
            'name'         => $this->faker->words(3, true),
            'brand'        => $this->faker->company(),
            'price'        => $this->faker->numberBetween(100, 10000),
            'description'  => $this->faker->sentence(),
            'image_url'    => 'test_image.png',
            'buyer_id'     => null,
        ];
    }
}