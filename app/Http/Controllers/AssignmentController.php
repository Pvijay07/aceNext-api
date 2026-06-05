<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Assignment;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $courseId = $request->query('course_id');
        $query = Assignment::query();
        if ($courseId) {
            $query->where('course_id', $courseId);
        }
        return response()->json($query->latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|string',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $assignment = Assignment::create($validated);
        return response()->json($assignment, 201);
    }
}
