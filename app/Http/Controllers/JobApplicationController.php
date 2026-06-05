<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\JobApplication;

class JobApplicationController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Applied,Shortlisted,Interview Scheduled,Selected'
        ]);

        $application = JobApplication::findOrFail($id);
        
        $application->status = $request->status;
        $application->save();

        return response()->json(['message' => 'Application status updated.', 'application' => $application]);
    }
}
