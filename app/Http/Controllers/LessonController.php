<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Service\ImageService;

class LessonController extends Controller
{

    public function index()
    {
        return $this->successResponse(Lesson::all());
    }

    public function store(LessonRequest $request, ImageService $imageService)
    {
        return $this->handleRequest(function () use ($request, $imageService) {
            $validated = $request->validated();
            $validated['lesson_pdf'] = $imageService->uploadImage($validated['lesson_pdf'], "lessons");
            $lesson = Lesson::create($validated);
            return $this->successResponse($lesson);
        });
    }

    public function show($id)
    {
        return $this->handleRequest(function () use ($id) {
            $lesson = Lesson::findOrFail($id);
            return $this->successResponse($lesson);
        });
    }

    public function update(LessonRequest $request, $id, ImageService $imageService)
    {
        return $this->handleRequest(function () use ($request, $id, $imageService) {
            $lesson = Lesson::findOrFail($id);
            $validated = $request->validated();
            if ($validated['lesson_pdf']) {
                $validated['lesson_pdf'] = $imageService->uploadImage($validated['lesson_pdf'], "lessons", $lesson->lesson_pdf);
            }
            $lesson->update($validated);
            return $this->successResponse($lesson);
        });
    }

    public function destroy($id)
    {
        return $this->handleRequest(function () use ($id) {
            $lesson = Lesson::findOrFail($id);
            $lesson->delete();
            return $this->successResponse($lesson);
        });
    }

    public function finished($id)
    {
        return $this->handleRequest(function () use ($id) {
            $lesson = Lesson::findOrFail($id);
            $lesson->update(['finished' => true]);
            $course = Course::findOrFail($lesson->course_id);

            $totalLessons = Lesson::where('course_id', $course->id)->count();
            $finishedLessons = Lesson::where('course_id', $course->id)->where('finished', true)->count();
            $completionPercentage = number_format(($finishedLessons / $totalLessons) * 100, 2);

            $course->update(['completed' => $completionPercentage]);
            return $this->successResponse(['message' => 'Lesson finished successfully']);
        });
    }
}
