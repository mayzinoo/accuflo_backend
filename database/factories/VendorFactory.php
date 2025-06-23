<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'name',
            'code' => 'code',
            'invoice_due_date' => '0',
            'invoice_due_date_unit' => '0',
            'status' => 'no',
            'address_line_1' => '',
            'address_line_2' => '',
            'city' => '',
            'state' => '',
            'country_code' => '',
            'postal_code' => '',
            'phone' => '',
            'cell' => '',
            'fax' => '',
            'email' => '',
            'notes' => ''
        ];
    }
}
