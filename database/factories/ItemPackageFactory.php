<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemPackageFactory extends Factory
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
            'item_size_id' => 1 ,
            'qty' => 1 ,
            'unit_from' => 'BOTTLE',
            'unit_to' => 'BOTTLE',
            'package_barcode' => rand(1000,100000),
        ];
    }
}
