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

    public function packages()
    {
        return response()->json(\App\Models\Package::all());
    }

    public function storePackage(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'price_display' => 'nullable|string',
        ]);

        $package = \App\Models\Package::create([
            'name' => $request->name,
            'price' => $request->price,
            'price_display' => $request->price_display ?? ('₹' . number_format($request->price)),
            'users' => 0,
            'active' => true,
        ]);

        return response()->json($package, 201);
    }

    public function updatePackageStatus(Request $request, $id)
    {
        $package = \App\Models\Package::findOrFail($id);
        $package->update([
            'active' => (bool)$request->active
        ]);

        return response()->json($package);
    }
}
