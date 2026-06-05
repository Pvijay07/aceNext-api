<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\LiveClass;
use App\Models\ClassAttendance;

class LiveClassController extends Controller
{
    public function index(Request $request)
    {
        $courseId = $request->query('course_id');
        $query = LiveClass::query();
        if ($courseId) {
            $query->where('course_id', $courseId);
        }
        return response()->json($query->orderBy('date', 'asc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|string',
            'topic' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'meeting_link' => 'nullable|url',
        ]);

        $liveClass = LiveClass::create($validated);
        return response()->json($liveClass, 201);
    }

    public function join(Request $request, $id)
    {
        $liveClass = LiveClass::findOrFail($id);
        ClassAttendance::updateOrCreate(
            ['live_class_id' => $liveClass->id, 'user_id' => $request->user()->id],
            ['join_time' => now()]
        );
        return response()->json(['message' => 'Attendance recorded successfully', 'meeting_link' => $liveClass->meeting_link]);
    }
}
