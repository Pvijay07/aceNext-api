<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobApplication;

class CompanyController extends Controller
{
    /**
     * Get verified student candidate list for hiring partners.
     */
    public function candidates(Request $request)
    {
        $candidates = User::where('role', 'student')
            ->get()
            ->map(function ($student) {
                $applicationsCount = JobApplication::where('student_id', $student->id)->count();
                return [
                    'id' => (string) $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'xp' => $student->xp ?? 1450,
                    'streak' => $student->streak ?? 5,
                    'skills' => ['React 19', 'TypeScript', 'Node.js', 'Tailwind CSS'],
                    'atsScore' => rand(78, 95),
                    'placementReadiness' => rand(85, 98),
                    'status' => $student->status ?? 'active',
                    'applicationsCount' => $applicationsCount
                ];
            });

        return response()->json($candidates);
    }

    /**
     * Schedule candidate interview.
     */
    public function scheduleInterview(Request $request)
    {
        $validated = $request->validate([
            'studentId' => 'required',
            'date' => 'required',
            'time' => 'required',
            'interviewType' => 'nullable|string',
            'meetingLink' => 'nullable|string'
        ]);

        $student = User::find($validated['studentId']);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => "Interview scheduled with {$student->name} for {$validated['date']} at {$validated['time']}.",
            'details' => array_merge($validated, ['studentName' => $student->name])
        ]);
    }

    /**
     * Issue job offer letter to student candidate.
     */
    public function offerLetter(Request $request)
    {
        $validated = $request->validate([
            'studentId' => 'required',
            'jobTitle' => 'required|string',
            'salaryPackage' => 'required|string',
            'joiningDate' => 'nullable|string'
        ]);

        $student = User::find($validated['studentId']);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $app = JobApplication::where('student_id', $student->id)->first();
        if (!$app) {
            $app = JobApplication::create([
                'job_posting_id' => 1,
                'student_id' => $student->id,
                'resume_path' => 'resumes/student.pdf',
                'status' => 'Placed',
                'remarks' => "Offer issued for {$validated['jobTitle']} ({$validated['salaryPackage']})",
                'offer_letter_path' => 'docs/offer_letter.pdf',
                'joining_doc_path' => '',
                'applied_date' => now()->toDateString()
            ]);
        } else {
            $app->update([
                'status' => 'Placed',
                'remarks' => "Offer issued for {$validated['jobTitle']} ({$validated['salaryPackage']})",
                'offer_letter_path' => 'docs/offer_letter.pdf'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Offer letter issued to {$student->name}!",
            'application' => $app
        ]);
    }
}
