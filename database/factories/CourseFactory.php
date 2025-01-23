<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'day' => $this->faker->dayOfWeek(),
            'time' => $this->faker->time(),
            'course_image' => $this->faker->imageUrl(),
            'teacher_id' => 1
        ];
    }
}
