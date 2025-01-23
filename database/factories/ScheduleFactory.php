<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'day' => $this->faker->dayOfWeek(),
            'time' => $this->faker->time(),
            'course_id' => 1
        ];
    }
}
