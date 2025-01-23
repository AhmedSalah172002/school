<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Teacher;
use App\Models\User;
use Tests\TestCase;

class LessonTest extends TestCase
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

    public function test_get_lessons_by_admin(): void
    {
        [$user, $header] = $this->loginUser();
        Admin::factory()->create(['user_id' => $user->id]);
        $course = Course::factory()->create();
        $lessons = Lesson::factory()->count(3)->create(['course_id' => $course->id]);

        $response = $this->get('api/lessons', $header);
        $response->assertStatus(200);

    }

    public function test_create_lesson_by_admin(): void
    {
        [$user, $header] = $this->loginUser();
        Admin::factory()->create(['user_id' => $user->id]);
        $course = Course::factory()->create();

        $response = $this->post('api/lessons', [
            'title' => 'lesson title',
            'course_id' => $course->id,
            'description' => 'lesson description',
            'lesson_pdf' => '/media/ahmed/HDD/laravel_tutorial.pdf'
        ], $header);

        $response->assertStatus(201);
    }

    public function test_update_lesson_by_admin(): void
    {
        [$user, $header] = $this->loginUser();
        Admin::factory()->create(['user_id' => $user->id]);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        $response = $this->put('api/lessons/' . $lesson->id, [
            'title' => 'lesson title',
            'course_id' => $course->id,
            'description' => 'lesson description 1',
            'lesson_pdf' => '/media/ahmed/HDD/laravel_tutorial.pdf'
        ], $header);

        $response->assertStatus(200);
    }

    public function test_delete_lesson_by_admin(): void
    {
        [$user, $header] = $this->loginUser();
        Admin::factory()->create(['user_id' => $user->id]);
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        $response = $this->delete('api/lessons/' . $lesson->id, [], $header);

        $response->assertStatus(200);
    }

    public function test_finished_lesson_by_teacher(): void
    {
        [$user, $header] = $this->loginUser();
        $teacher = Teacher::factory()->create(['user_id' => $user->id]);

        $course = Course::factory()->create(['teacher_id' => $teacher->id]);

        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        $response = $this->put('api/lesson/finished/' . $lesson->id, [], $header);

        $response->assertStatus(200);
    }

    public function test_finished_lesson_by_another_teacher(): void
    {
        [$user, $header] = $this->loginUser();
        Teacher::factory()->create(['user_id' => $user->id]);

        $newTeacher = User::factory()->create();

        $newTeacher = Teacher::factory()->create(['user_id' => $newTeacher->id]);

        $course = Course::factory()->create(['teacher_id' => $newTeacher->id]);

        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        $response = $this->put('api/lesson/finished/' . $lesson->id, [], $header);

        $response->assertStatus(403);
    }
}
