<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\User;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::with(['course', 'faculty'])->get()->map(function ($b) {
            return [
                'id' => $b->id,
                'name' => $b->name,
                'course_id' => $b->course_id,
                'faculty_id' => $b->faculty_id,
                'start_date' => $b->start_date,
                'end_date' => $b->end_date,
                'capacity' => $b->capacity,
                'status' => $b->status,
                'delay_reason' => $b->delay_reason,
                'expected_completion_date' => $b->expected_completion_date,
                'course_name' => $b->course ? $b->course->title : '—',
                'faculty_name' => $b->faculty ? $b->faculty->name : '—',
                'enrolled_count' => $b->students()->count(),
            ];
        });

        return response()->json($batches);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'course_id' => 'required|integer',
            'faculty_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'capacity' => 'nullable|integer',
        ]);

        $batch = Batch::create([
            'name' => $request->name,
            'course_id' => $request->course_id,
            'faculty_id' => $request->faculty_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'capacity' => $request->capacity ?? 30,
            'status' => 'Starting Soon',
        ]);

        return response()->json($batch, 201);
    }

    public function update(Request $request, $id)
    {
        $batch = Batch::findOrFail($id);
        $batch->update($request->only([
            'name', 'course_id', 'faculty_id', 'start_date', 'end_date', 'capacity', 'status', 'delay_reason', 'expected_completion_date'
        ]));

        return response()->json($batch);
    }

    public function destroy($id)
    {
        $batch = Batch::findOrFail($id);
        $batch->students()->detach();
        $batch->delete();

        return response()->json(['success' => true]);
    }

    public function assignStudent(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|integer',
        ]);

        $batch = Batch::findOrFail($id);
        if (!$batch->students()->where('student_id', $request->student_id)->exists()) {
            $batch->students()->attach($request->student_id, ['status' => 'enrolled']);
        }

        return response()->json(['success' => true]);
    }

    public function removeStudent(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|integer',
        ]);

        $batch = Batch::findOrFail($id);
        $batch->students()->detach($request->student_id);

        return response()->json(['success' => true]);
    }

    public function transferStudent(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'target_batch_id' => 'required|integer',
        ]);

        $batch = Batch::findOrFail($id);
        $batch->students()->detach($request->student_id);

        $target = Batch::findOrFail($request->target_batch_id);
        if (!$target->students()->where('student_id', $request->student_id)->exists()) {
            $target->students()->attach($request->student_id, ['status' => 'enrolled']);
        }

        return response()->json(['success' => true]);
    }

    public function assignFaculty(Request $request, $id)
    {
        $request->validate([
            'faculty_id' => 'required|integer',
        ]);

        $batch = Batch::findOrFail($id);
        $batch->faculty_id = $request->faculty_id;
        $batch->save();

        return response()->json(['success' => true, 'batch' => $batch]);
    }
}
