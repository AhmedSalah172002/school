<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'role' => 'Teacher',
            'expert_years' => $this->faker->numberBetween($min = 1, $max = 50),
            'bio' => $this->faker->realText($maxNbChars = 200, $indexSize = 2),
        ];
    }
}
