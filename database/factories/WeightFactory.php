<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WeightFactory extends Factory
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
            'section' => 'Main',
            'shelf' => '0',
            'last_peroid_weight' => '0',
            'last_peroid_weight_unit' => 'g',
            'current_peroid_weight' => '10',
            'current_peroid_weight_unit' => 'g',
            'volume_different' => '10.0',
            'volume_different_unit' => 'g/ml'
        ];
    }
}
