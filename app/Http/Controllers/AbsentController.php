<?php

namespace App\Http\Controllers;

use App\Models\Absent;
use App\Models\Lesson;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AbsentController extends Controller
{
    public function store(Request $request){
        return $this->handleRequest(function () use ($request) {
           $validatedData = $request->validate([
               'lesson_id' => 'required|exists:lessons,id',
               'student_id' => 'required|exists:students,id',
           ]);
            $exists = Absent::where('lesson_id', $validatedData['lesson_id'])
                ->where('student_id', $validatedData['student_id'])
                ->exists();
            if ($exists) {
                return $this->errorResponse('This student already absent from this lesson.', 409);
            }
           $absent = Absent::create($validatedData);
           return $this->successResponse($absent , 201);
        });
    }

    public function absentStudentsByLesson($lesson_id)
    {
        return $this->handleRequest(function () use ($lesson_id) {
           $absents = Absent::where(['lesson_id' => $lesson_id])->select('student_id')->get();
           $students = [];
           foreach ($absents as $absent) {
               $students[] = Student::with('user')->findOrFail($absent->student_id);
           }
           return $this->successResponse($students);
        });
    }


    public function absentStudentInLessons($student_id)
    {
        return $this->handleRequest(function () use ($student_id) {
            Log::info('hahaha');
            $absents = Absent::where(['student_id' => $student_id])->select('lesson_id')->get();
            Log::info($absents);
            $lessons = [];
            foreach ($absents as $absent) {
                $lessons[] = Lesson::findOrFail($absent->lesson_id);
            }
            return $this->successResponse($lessons);
        });
    }
}
