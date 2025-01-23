<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use DatabaseTransactions;

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

    public function test_get_courses_by_any_user(): void
    {
        [, $header] = $this->loginUser();

        $courseResponse = $this->get(route('our-courses'), $header);
        $courseResponse->assertStatus(200);
    }

    public function test_create_courses_by_admin(): void
    {

        [$user, $header] = $this->loginUser();
        Admin::factory()->create(['user_id' => $user->id]);

        $courseResponse = $this->post('/api/courses', [
            'title' => 'course title',
            'description' => 'course description',
            'day' => 'Thursday',
            'time' => '10:05',
            'price' => 25,
            'course_image' => '/media/ahmed/HDD/laravel.jpeg',
        ], $header);

        $courseResponse->assertStatus(201);
    }

    public function test_create_courses_by_any_user_except_admin(): void
    {
        [$user, $header] = $this->loginUser();
        Student::factory()->create(['user_id' => $user->id]);

        $courseResponse = $this->post('/api/courses', [
            'title' => 'course title',
            'description' => 'course description',
            'day' => 'Thursday',
            'time' => '10:05',
            'price' => 25,
            'course_image' => '/media/ahmed/HDD/laravel.jpeg',
        ], $header);

        $courseResponse->assertStatus(403);

    }

    public function test_update_courses_by_admin(): void
    {
        [$user, $header] = $this->loginUser();
        Admin::factory()->create(['user_id' => $user->id]);

        $course = Course::factory()->create();
        $schedule = Schedule::factory()->create(['course_id' => $course->id]);


        $courseResponse = $this->put("/api/courses/{$course->id}", [
            'title' => 'course title',
            'description' => 'course description',
            'day' => 'Thursday',
            'time' => '10:05',
            'price' => 25,
            'course_image' => '/media/ahmed/HDD/laravel.jpeg',
        ], $header);

        $courseResponse->assertStatus(200);
    }

    public function test_delete_course_by_admin(): void
    {
        [$user, $header] = $this->loginUser();
        Admin::factory()->create(['user_id' => $user->id]);
        $course = Course::factory()->create();
        $courseResponse = $this->delete("/api/courses/{$course->id}", [], $header);
        $courseResponse->assertStatus(200);
    }

    public function test_get_students_in_course(): void
    {
        [$user, $header] = $this->loginUser();
        Admin::factory()->create(['user_id' => $user->id]);
        $course = Course::factory()->create();

        $response = $this->get("api/courses/{$course->id}/students", $header);
        $response->assertStatus(200);
    }

    public function test_get_lessons_in_course(): void
    {
        [$user, $header] = $this->loginUser();
        Admin::factory()->create(['user_id' => $user->id]);
        $course = Course::factory()->create();
        $response = $this->get("api/courses/{$course->id}/lessons", $header);
        $response->assertStatus(200);
    }
}
