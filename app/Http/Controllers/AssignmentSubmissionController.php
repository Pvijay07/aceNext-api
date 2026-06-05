<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AssignmentSubmission;
use Illuminate\Support\Facades\Storage;

class AssignmentSubmissionController extends Controller
{
    public function submit(Request $request, $assignmentId)
    {
        $request->validate([
            'text_answer' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $path = null;
        $fileType = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('submissions', 'public');
            $fileType = $request->file('file')->getClientOriginalExtension();
        }

        // Mock AI Evaluation
        $aiScore = rand(70, 95);
        $aiFeedback = "Good effort on the structure. Ensure you cover all edge cases in the requirements.";

        $submission = AssignmentSubmission::updateOrCreate(
            ['assignment_id' => $assignmentId, 'user_id' => $request->user()->id],
            [
                'text_answer' => $request->text_answer,
                'file_path' => $path,
                'file_type' => $fileType,
                'ai_score' => $aiScore,
                'ai_feedback' => $aiFeedback,
            ]
        );

        return response()->json($submission, 201);
    }

    public function pendingReviews(Request $request)
    {
        $submissions = AssignmentSubmission::with(['assignment', 'user'])
            ->whereNull('faculty_score')
            ->latest()
            ->get();
        return response()->json($submissions);
    }

    public function grade(Request $request, $submissionId)
    {
        $request->validate([
            'faculty_score' => 'required|integer|min:0|max:100',
            'faculty_feedback' => 'nullable|string',
        ]);

        $submission = AssignmentSubmission::findOrFail($submissionId);
        $submission->faculty_score = $request->faculty_score;
        $submission->faculty_feedback = $request->faculty_feedback;
        $submission->save();

        return response()->json($submission);
    }
}
