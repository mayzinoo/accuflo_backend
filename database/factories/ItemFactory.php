<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => Str::random(4),
            'class_id' => '1',
            'category_id' => '1',
            'quality_id' => '1',
            'user_id' => '2',
            'period_id' => 1
        ];
    }
}
