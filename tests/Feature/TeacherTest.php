<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\User;
use Tests\TestCase;

class TeacherTest extends TestCase
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

    public function test_get_teachers_by_admin(): void
    {
        [$user, $header] = $this->loginUser();
        Admin::factory()->create(['user_id' => $user->id]);
        $response = $this->get('/api/teachers', $header);
        $response->assertStatus(200);
    }

    public function test_update_teachers_by_admin(): void
    {
        [$user, $header] = $this->loginUser();
        Admin::factory()->create(['user_id' => $user->id]);
        $newUser = User::factory()->create();
        $teacher = Teacher::factory()->create(['user_id' => $newUser->id]);

        $response = $this->put("/api/teachers/{$teacher->id}", [
            'bio' => 'this is a bio',
            'expert_years' => 6
        ], $header);

        $response->assertStatus(200);
    }

    public function test_delete_teachers_by_admin(): void
    {
        [$user, $header] = $this->loginUser();
        Admin::factory()->create(['user_id' => $user->id]);

        $newUser = User::factory()->create();
        $teacher = Teacher::factory()->create(['user_id' => $newUser->id]);

        $response = $this->delete("/api/teachers/{$teacher->id}", [], $header);
        $response->assertStatus(200);
    }

    public function test_enroll_teachers_by_admin(): void
    {
        [$user, $header] = $this->loginUser();
        Admin::factory()->create(['user_id' => $user->id]);
        $newUser = User::factory()->create();
        $teacher = Teacher::factory()->create(['user_id' => $newUser->id]);
        $course = Course::factory()->create();
        $schedule = Schedule::factory()->create([
            'day' => $course->day,
            'time' => $course->time,
            'course_id' => $course->id,
        ]);
        $response = $this->post("/api/teacher/enroll/{$teacher->id}", [
            'course_id' => $course->id,
        ], $header);

        $response->assertStatus(200);

    }

    public function test_approved_teacher_by_admin(): void
    {
        [$user, $header] = $this->loginUser();
        Admin::factory()->create(['user_id' => $user->id]);
        $newUser = User::factory()->create();
        $teacher = Teacher::factory()->create(['user_id' => $newUser->id]);

        $response = $this->put("/api/teacher/approved/{$teacher->id}", [], $header);
        $response->assertStatus(200);
    }

    public function test_declined_teacher_by_admin(): void
    {
        [$user, $header] = $this->loginUser();
        Admin::factory()->create(['user_id' => $user->id]);
        $newUser = User::factory()->create();
        $teacher = Teacher::factory()->create(['user_id' => $newUser->id]);

        $response = $this->put("/api/teacher/denied/{$teacher->id}", [
            'reason' => 'this is a reason',
        ], $header);

        $response->assertStatus(200);
    }

}
