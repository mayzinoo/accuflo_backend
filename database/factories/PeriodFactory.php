<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class PeriodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::where('role', 'client')->first();
        return [
            'user_id' => $user->id,
            'start_date' => now(),
            'end_date' => now(),
            'status' => '1',
        ];
    }
}
