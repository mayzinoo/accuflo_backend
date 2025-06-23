<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ItemSize;
use App\GlobalConstants;
use Illuminate\Support\Str;

class ItemSizeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'item_id' => 1 ,
            // 'barcode' => rand(1000,100000),
            'countable_unit' => rand(1,20),
            'countable_size' => $this->faker->randomElement(['ml','L','oz']),
            'empty_weight' => rand(1,10),
            'empty_weight_size' => $this->faker->randomElement(['g','kg','dryoz']),
            'full_weight' => rand(15,40),
            'full_weight_size' => $this->faker->randomElement(['kg','dryoz']),
            'density' => rand(2,40),
            'density_m_unit' => $this->faker->randomElement(['dryoz','g','kg']),
            'density_v_unit' => $this->faker->randomElement(['hL','L']),
            'sizeoption' => 'yes' ,
            'quantification' => 'no',
            'package_status' => 'no',
        ];
    }
}
