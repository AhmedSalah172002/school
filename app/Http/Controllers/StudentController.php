<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Service\StudentService;

class StudentController extends Controller
{
    public function index(){
        return $this->successResponse(Student::all());
    }

    public function enrollToCourse($id , StudentService $studentService ){

       return $this->handleRequest(function() use ($id , $studentService ){
           $user = auth()->user();
           return $studentService->enroll($id , $user );
       });
    }

    public function studentCourses(){

        return $this->handleRequest(function(){
            $user = auth()->user();
            $student = Student::where(['user_id' => $user->id])->first();
            $courses = $student->courses;
            return $this->successResponse($courses);
        });
    }

}
