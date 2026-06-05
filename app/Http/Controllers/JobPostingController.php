<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\JobPosting;
use App\Models\JobApplication;

class JobPostingController extends Controller
{
    public function index()
    {
        return response()->json(JobPosting::all());
    }

    public function apply(Request $request, $id)
    {
        $job = JobPosting::findOrFail($id);
        
        $application = JobApplication::firstOrCreate([
            'user_id' => $request->user()->id,
            'job_posting_id' => $job->id
        ], [
            'status' => 'Applied'
        ]);

        return response()->json(['message' => 'Successfully applied to job.', 'application' => $application]);
    }
}
