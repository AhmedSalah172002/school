<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherRequest;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class TeacherController extends Controller
{
    public function index()
    {
        return $this->successResponse(Teacher::all());
    }

    public function show($id)
    {
        return $this->handleRequest(function () use ($id) {
            $teacher = Teacher::findOrFail($id);
            return $this->successResponse($teacher);
        });
    }

    public function update(TeacherRequest $request, $id)
    {
        return $this->handleRequest(function () use ($request, $id) {
            $validated = $request->validated();
            $teacher = Teacher::findOrFail($id);
            $teacher->update($validated);
            return $this->successResponse($teacher);
        });
    }

    public function destroy($id)
    {
        return $this->handleRequest(function () use ($id) {
            $teacher = Teacher::findOrFail($id);
            $teacher->delete();
            return $this->successResponse(['message' => 'Teacher deleted successfully.']);
        });
    }

    public function teacherProfile(TeacherRequest $request)
    {
        {
            return $this->handleRequest(function () use ($request) {
                $validated = $request->validated();
                $user = auth()->user();
                $teacher = Teacher::where(['user_id' => $user->id])->first();
                $teacher->update($validated);
                return $this->successResponse($teacher);
            });
        }
    }

    public function enrollTeacherToCourse($id, Request $request)
    {
        return $this->handleRequest(function () use ($id, $request) {
            $teacher = Teacher::findOrFail($id);
            $course = Course::findOrFail($request->course_id);
            $course->update(['teacher_id' => (integer)$id]);
            return $this->successResponse($course);
        });
    }

    public function activeTeachers()
    {
        return $this->successResponse(Teacher::where(['status' => true])->get());
    }

    public function approvedTeacher($id)
    {
        return $this->handleRequest(function () use ($id) {
            $teacher = Teacher::findOrFail($id);
            $teacher->update(['status' => true]);
            return $this->successResponse(['message' => 'Teacher approved successfully.']);
        });
    }

    public function denyTeacher(Request $request, $id)
    {
        return $this->handleRequest(function () use ($id, $request) {
            $teacher = Teacher::findOrFail($id);
            $teacher->update(['status' => false]);
            return $this->successResponse(['message' => 'Teacher Denied successfully.', 'reason' => $request->reason]);
        });
    }
}
