<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FullCountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'item_id' => '1',
            'station_id' => '1',
            'period_id' => '1',
            'last_peroid_count' => '10',
            'current_peroid_count' => '10',
            'invertory_level' => '10',
        ];
    }
}
