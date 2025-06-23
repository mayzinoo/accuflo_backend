<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PriceLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'station_id' => '1',
            'client_id' => '1',
            'period_id' => '1',
            'level' => 'Regular',
            'type' => '0'
        ];
    }
}
