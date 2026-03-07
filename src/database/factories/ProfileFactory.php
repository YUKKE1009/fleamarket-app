<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id'   => User::factory(),
            'image_url' => 'test_profile.png',
            'post_code' => $this->faker->postcode(),
            'address'   => $this->faker->address(),
            'building'  => $this->faker->secondaryAddress(),
        ];
    }
}
