<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Schedule;
use App\Service\ImageService;
use App\Service\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index()
    {
        return $this->successResponse(Course::all());
    }

    public function store(CourseRequest $request, ImageService $imageService)
    {
        return $this->handleRequest(function () use ($request, $imageService) {
            $validated = $request->validated();
            $validated['course_image'] = $imageService->uploadImage($validated['course_image'], "courses");
            $validated['course_qrcode'] = $imageService->uploadQrcode(collect($validated)->except(['course_image']), "courses");
            DB::beginTransaction();
            $course = Course::create($validated);
            $schedule = Schedule::create([
                'course_id' => $course->id,
                'day' => $validated['day'],
                'time' => $validated['time'],
            ]);
            DB::commit();
            return $this->successResponse([
                'course' => $course,
            ], 201);
        });
    }

    public function show($id)
    {
        return $this->handleRequest(function () use ($id) {
            $course = Course::findOrFail($id);
            return $this->successResponse($course);
        });
    }

    public function update(CourseRequest $request, $id, ImageService $imageService)
    {
        return $this->handleRequest(function () use ($request, $id, $imageService) {
            $course = Course::findOrFail($id);
            $validated = $request->validated();
            $validated['course_image'] = $imageService->uploadImage($validated['course_image'], "courses", $course->course_image);
            $validated['course_qrcode'] = $imageService->uploadQrcode(collect($validated)->except(['course_image']), "courses", $course->course_qrcode);
            $course->update($validated);
            $schedule = Schedule::where(['course_id' => $course->id])->first();
            $schedule->update([
                'day' => $validated['day'],
                'time' => $validated['time'],
            ]);
            return $this->successResponse($course);
        });
    }

    public function destroy($id)
    {
        return $this->handleRequest(function () use ($id) {
            $course = Course::findOrFail($id);
            $course->delete();
            return $this->successResponse(['message' => 'Course deleted successfully']);
        });
    }

    public function CourseStudents($id)
    {
        return $this->handleRequest(function () use ($id) {
            $course = Course::findOrFail($id);
            return $this->successResponse($course->students);
        });
    }

    public function CourseLessons($id)
    {
        return $this->handleRequest(function () use ($id) {
            $course = Course::findOrFail($id);
            return $this->successResponse($course->lessons);
        });
    }

    public function generateLessonsByAi(Request $request ,$id, OpenAIService  $service){
        return $this->handleRequest(function () use ($id, $request, $service) {
            $course = Course::findOrFail($id);
            $lessons = $service->generateText("Generate {$request->lessons_count} lesson titles about {$course->title}. The response should be an array of JSON objects like JavaScript, and only contain the data in the following format: [{title: 'x', description: 'z'}, {title: 'x', description: 'z'}, {title: 'x', description: 'z'}]. Do not write anything else outside the array. The response must start with [ and end with ].");
            foreach($lessons as $lesson){
                Lesson::create([
                    "title" => $lesson->title,
                    "description" => $lesson->description,
                    "course_id" => $course->id,
                ]);
            }
            return $this->successResponse(['message' => 'Lessons generated successfully'] , 201);
        });
    }
}
