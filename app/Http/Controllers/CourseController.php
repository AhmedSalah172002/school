<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Http\Requests\CourseUpdateRequest;
use App\Models\Course;
use App\Service\ImageService;

class CourseController extends Controller
{
    public function index()
    {
        return $this->successResponse(Course::all());
    }

    public function store(CourseRequest $request , ImageService $imageService)
    {
        return  $this->handleRequest(function() use ($request, $imageService){
            $validated = $request->validated();
            $validated['course_image'] = $imageService->uploadImage($validated['course_image'] , "courses");
            $course = Course::create($validated);
            return $this->successResponse($course , 201);
        });
    }

    public function show($id)
    {
        return $this->handleRequest(function () use ($id) {
            $course = Course::findOrFail($id);
            return $this->successResponse($course);
        });
    }

    public function update(CourseUpdateRequest $request,  $id , ImageService $imageService)
    {
        return $this->handleRequest(function () use ($request, $id , $imageService) {
            $course = Course::findOrFail($id);
            $validated = $request->validated();
            $validated['course_image'] = $imageService->uploadImage($validated['course_image'] , "courses" , $course->course_image);
            $course->update($validated);
            return $this->successResponse($course);
        });
    }

    public function destroy( $id)
    {
        return $this->handleRequest(function () use ($id) {
            $course = Course::findOrFail($id);
            $course->delete();
            return $this->successResponse(['message' => 'Course deleted successfully']);
        });
    }

    public function CourseStudents($id){
        return $this->handleRequest(function () use ($id){
           $course = Course::findOrFail($id);
           return $this->successResponse($course->students);
        });
    }
    public function CourseLessons($id){
        return $this->handleRequest(function () use ($id){
            $course = Course::findOrFail($id);
            return $this->successResponse($course->lessons);
        });
    }
}
