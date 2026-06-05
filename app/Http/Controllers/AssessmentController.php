<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;

class AssessmentController extends Controller
{
    public function index()
    {
        return response()->json(Assessment::with('questions.options')->get());
    }

    public function show($id)
    {
        return response()->json(Assessment::with('questions.options')->findOrFail($id));
    }

    public function submit(Request $request, $id)
    {
        $request->validate([
            'responses' => 'required|array',
            'score' => 'required|numeric'
        ]);

        $attempt = AssessmentAttempt::create([
            'user_id' => $request->user()->id,
            'assessment_id' => $id,
            'score' => $request->score,
            'status' => $request->score >= 70 ? 'passed' : 'failed',
            'responses' => $request->responses
        ]);

        return response()->json(['message' => 'Assessment submitted successfully', 'attempt' => $attempt]);
    }
}
