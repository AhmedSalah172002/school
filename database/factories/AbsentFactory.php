<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AbsentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'lesson_id' => 1,
            'student_id' => 1,
        ];
    }
}
