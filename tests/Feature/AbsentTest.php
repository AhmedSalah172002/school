<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Tests\TestCase;

class AbsentTest extends TestCase
{

    private function loginUser(array $overrides = []): array
    {
        $user = User::factory()->create($overrides);
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(201);

        $token = $response->json()['data']['token'];
        $header = ['Authorization' => 'Bearer ' . $token];

        return [$user, $header];
    }

    public function test_create_absent_by_teacher(): void
    {
        [$user, $header] = $this->loginUser();
        Teacher::factory()->create(['user_id' => $user->id]);
        $newUser = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $newUser->id]);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        $response = $this->post('api/absents', [
            'lesson_id' => $lesson->id,
            'student_id' => $student->id,
        ], $header);

        $response->assertStatus(201);
    }

    public function test_get_absent_students_by_teacher(): void
    {
        [$user, $header] = $this->loginUser();
        Teacher::factory()->create(['user_id' => $user->id]);
        $newUser = User::factory()->create();
        $student = Student::factory()->create(['user_id' => $newUser->id]);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        $response = $this->get("api/absents/students/{$student->id}", $header);

        $response->assertStatus(200);
    }
}
