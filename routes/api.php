<?php

use App\Http\Controllers\AbsentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\StudentRole;
use App\Http\Middleware\TeacherRole;
use App\Service\WebhookService;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Middleware\AdminRole;


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login'])->name('login');

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('profile', [UserController::class, 'getUser']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('our-courses', [CourseController::class, 'index'])->name('our-courses');
});

Route::middleware([StudentRole::class])->group(function () {
    Route::post('enroll/course/{id}', [StudentController::class, 'enrollToCourse']);
    Route::get('student/courses', [StudentController::class, 'studentCourses']);
});

Route::middleware([TeacherRole::class])->group(function () {
    Route::put('teacher/profile', [TeacherController::class, 'teacherProfile']);
    Route::post('absents', [AbsentController::class, 'store']);
    Route::get('absents/students/{student_id}', [AbsentController::class, 'absentStudentInLessons']);
    Route::get('absents/{lesson_id}', [AbsentController::class, 'absentStudentsByLesson']);
    Route::put('lesson/finished/{id}', [LessonController::class, 'finished']);

});

Route::middleware([AdminRole::class])->group(function () {
    Route::ApiResource('courses', CourseController::class);
    Route::ApiResource('teachers', TeacherController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::put('teacher/approved/{id}', [TeacherController::class, 'approvedTeacher']);
    Route::put('teacher/denied/{id}', [TeacherController::class, 'denyTeacher']);
    Route::get('active/teachers', [TeacherController::class, 'activeTeachers']);
    Route::post('teacher/enroll/{id}', [TeacherController::class, 'enrollTeacherToCourse']);
    Route::ApiResource('lessons', LessonController::class);
    Route::get('students', [StudentController::class, 'index']);
    Route::get('courses/{id}/students', [CourseController::class, 'CourseStudents']);
    Route::get('courses/{id}/lessons', [CourseController::class, 'CourseLessons']);
});

Route::post('webhook', [WebhookService::class, 'webhook']);
