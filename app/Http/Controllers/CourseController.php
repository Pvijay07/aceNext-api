<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('modules.lessons')->get();
        return response()->json($courses);
    }

    public function show($slug)
    {
        $course = Course::with('modules.lessons')->where('slug', $slug)->orWhere('id', $slug)->firstOrFail();
        return response()->json($course);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'thumbnail_url' => 'nullable|string',
            'price' => 'nullable|numeric',
            'duration' => 'nullable|string',
            'status' => 'nullable|string',
            'mentor_id' => 'nullable|integer',
        ]);

        $course = Course::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'thumbnail_url' => $request->thumbnail_url ?? 'https://via.placeholder.com/800x600?text=Course',
            'price' => $request->price ?? 0.00,
            'duration' => $request->duration ?? '12 Weeks',
            'status' => $request->status ?? 'published',
            'mentor_id' => $request->mentor_id,
        ]);

        return response()->json($course, 201);
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $course->update($request->only([
            'title', 'description', 'thumbnail_url', 'price', 'duration', 'status', 'mentor_id'
        ]));

        if ($request->has('title')) {
            $course->slug = Str::slug($request->title);
            $course->save();
        }

        return response()->json($course);
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json(['success' => true]);
    }

    // Module CRUD methods
    public function indexModules()
    {
        $modules = CourseModule::all();
        return response()->json($modules);
    }

    public function storeModule(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'title' => 'required|string',
            'order_no' => 'nullable|integer',
        ]);

        $module = CourseModule::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'order_no' => $request->order_no ?? (CourseModule::where('course_id', $request->course_id)->count() + 1),
        ]);

        return response()->json($module, 201);
    }

    public function updateModule(Request $request, $id)
    {
        $module = CourseModule::findOrFail($id);
        $module->update($request->only(['title', 'order_no']));

        return response()->json($module);
    }

    public function destroyModule($id)
    {
        $module = CourseModule::findOrFail($id);
        $module->delete();

        return response()->json(['success' => true]);
    }
}
