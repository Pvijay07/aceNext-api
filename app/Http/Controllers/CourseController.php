<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('modules.lessons')->get();
        return response()->json($courses);
    }

    public function show($slug)
    {
        $course = Course::with('modules.lessons')->where('slug', $slug)->firstOrFail();
        return response()->json($course);
    }
}
