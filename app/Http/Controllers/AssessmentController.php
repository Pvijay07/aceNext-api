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

    public function skillEval(Request $request)
    {
        $answers = $request->input('answers', []);
        $targetCourse = $request->input('targetCourse', 'Full Stack Web Development');

        $score = 70;
        if (!empty($answers) && is_array($answers)) {
            $total = count($answers);
            $correct = count(array_filter($answers, fn($v) => $v === true || $v === 'correct'));
            $score = round(($correct / $total) * 100) ?: 68;
        }

        return response()->json([
            'score' => $score,
            'assessmentSummary' => "### 🎯 Baseline Assessment Result: {$score}%\nYou demonstrated solid logical reasoning and foundational coding principles for **{$targetCourse}**.\nFocus on strengthening asynchronous JavaScript, state architecture, and API security.",
            'skillBreakdown' => [
                ['skill' => 'Programming Fundamentals', 'proficiency' => min(100, $score + 10)],
                ['skill' => 'Data Structures & Logic', 'proficiency' => max(40, $score - 5)],
                ['skill' => 'Web Architecture', 'proficiency' => max(50, $score - 10)],
                ['skill' => 'Communication & Aptitude', 'proficiency' => 85]
            ],
            'recommendedPath' => [
                ['order' => 1, 'topic' => 'HTML5 & Semantic Web', 'duration' => '1 Week', 'status' => 'Recommended'],
                ['order' => 2, 'topic' => 'CSS3 Flexbox, Grid & Modern Layouts', 'duration' => '1.5 Weeks', 'status' => 'Recommended'],
                ['order' => 3, 'topic' => 'JavaScript Closures & ES6 Async/Await', 'duration' => '2 Weeks', 'status' => 'Recommended'],
                ['order' => 4, 'topic' => 'React 19 Hooks & State Rendering', 'duration' => '3 Weeks', 'status' => 'Recommended'],
                ['order' => 5, 'topic' => 'Node.js REST API & Express Middleware', 'duration' => '2.5 Weeks', 'status' => 'Recommended'],
                ['order' => 6, 'topic' => 'SQL & Database Isolation Levels', 'duration' => '2 Weeks', 'status' => 'Recommended']
            ]
        ]);
    }
}
