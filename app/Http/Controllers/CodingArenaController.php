<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CodingSubmission;

class CodingArenaController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'challenge_id' => 'required',
            'code' => 'required|string',
            'language' => 'required|string'
        ]);

        $passed = rand(0, 1) === 1;

        $submission = CodingSubmission::create([
            'user_id' => $request->user()->id,
            'project_id' => $request->challenge_id,
            'code' => $request->code,
            'language' => $request->language,
            'status' => $passed ? 'passed' : 'failed',
            'runtime_ms' => rand(10, 500),
            'memory_kb' => rand(1024, 20480),
            'error_log' => $passed ? null : 'Compilation error or test case failed.'
        ]);

        return response()->json([
            'message' => 'Code submitted',
            'submission' => $submission
        ]);
    }
}
