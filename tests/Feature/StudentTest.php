<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StudentTest extends TestCase
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

    public function test_get_users_by_admin(): void
    {
        [$user, $header] = $this->loginUser();
        Admin::factory()->create(['user_id' => $user->id]);

        $response = $this->get('/api/students', $header);
        $response->assertStatus(200);
    }

    public function test_student_enroll_to_course(): void
    {
        [$user, $header] = $this->loginUser();
        Student::factory()->create(['user_id' => $user->id]);
        $course = Course::factory()->create();

        $response = $this->post('/api/enroll/course/' . $course->id, [], $header);
        $response->assertStatus(201);

    }

    public function test_get_student_courses(): void
    {
        [$user, $header] = $this->loginUser();
        Student::factory()->create(['user_id' => $user->id]);

        $response = $this->get('/api/student/courses', $header);
        $response->assertStatus(200);

    }
}
