<?php


namespace App\Service;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;

class StudentService extends Controller
{

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function enroll($course_id, $user)
    {
        return $this->handleRequest(function () use ($course_id, $user) {
            $student = Student::where(['user_id' => $user->id])->first();
            if (!$student) {
                return $this->errorResponse("Student Not Found", 404);
            }
            if ($student->courses()->where('course_id', $course_id)->exists()) {
                return $this->errorResponse("Student already enrolled in this course", 404);
            }
            $course = Course::findOrFail($course_id);
            return $this->paymentService->payCourse($course, $user);
        });
    }
}
