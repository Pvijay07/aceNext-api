<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admission;
use App\Models\Batch;
use App\Models\InactiveStudentFollowUp;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('role_id', 3)->get()->map(function ($student) {
            $admission = Admission::where('email', $student->email)->first();
            
            // Get student's batch names
            $batches = \DB::table('batch_students')
                ->join('batches', 'batch_students.batch_id', '=', 'batches.id')
                ->where('batch_students.student_id', $student->id)
                ->pluck('batches.name')
                ->toArray();

            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone ?? ($admission ? $admission->phone : '—'),
                'qualification' => $student->qualification ?? ($admission ? $admission->qualification : '—'),
                'status' => $student->status,
                'batches' => $batches,
                'admission_status' => $admission ? $admission->status : 'Pending',
                'enrolled_course' => $admission && $admission->course ? $admission->course->title : '—',
            ];
        });

        return response()->json($students);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'qualification' => 'nullable|string',
            'course_id' => 'required|integer',
            'status' => 'nullable|string',
        ]);

        // Find or create basic user
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password'),
                'role_id' => 3, // student
                'status' => 'active',
            ]);
        }

        $admission = Admission::create([
            'student_name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'qualification' => $request->qualification,
            'course_id' => $request->course_id,
            'status' => $request->status ?? 'Pending',
            'document_url' => 'docs/default.pdf',
            'admission_date' => now()->toDateString(),
        ]);

        return response()->json([
            'user' => $user,
            'admission' => $admission
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'email', 'phone', 'status']));

        $admission = Admission::where('email', $user->email)->first();
        if ($admission) {
            $admission->update($request->only(['student_name', 'phone', 'qualification', 'course_id', 'status']));
        }

        return response()->json(['success' => true, 'user' => $user]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        Admission::where('email', $user->email)->delete();
        \DB::table('batch_students')->where('student_id', $id)->delete();
        $user->delete();

        return response()->json(['success' => true]);
    }

    public function updateAdmissionStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $user = User::findOrFail($id);
        $admission = Admission::where('email', $user->email)->first();
        if ($admission) {
            $admission->status = $request->status;
            $admission->remarks = $request->remarks;
            $admission->save();
        }

        if ($request->status === 'Rejected') {
            $user->status = 'inactive';
        } else if ($request->status === 'Approved') {
            $user->status = 'active';
        }
        $user->save();

        return response()->json(['success' => true, 'user' => $user, 'admission' => $admission]);
    }

    public function inactiveIndex()
    {
        $students = User::where('role_id', 3)->get()->map(function ($student) {
            $followUp = InactiveStudentFollowUp::where('student_id', $student->id)->first();
            $unsubmittedCount = \App\Models\Assignment::count() - \App\Models\AssignmentSubmission::where('user_id', $student->id)->count();
            
            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'last_active' => $followUp ? $followUp->last_active_date : now()->subDays(5)->toDateString(),
                'attendance_percentage' => 70, // Simulated logic
                'pending_assignments' => $unsubmittedCount,
                'contact_status' => $followUp ? $followUp->contact_status : 'Not Contacted',
                'remarks' => $followUp ? $followUp->remarks : '',
            ];
        })->filter(function($s) {
            return $s['attendance_percentage'] < 75 || $s['pending_assignments'] > 1;
        })->values();

        return response()->json($students);
    }

    public function followUp(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'remarks' => 'required|string',
            'contact_status' => 'nullable|string',
        ]);

        $followUp = InactiveStudentFollowUp::updateOrCreate(
            ['student_id' => $request->student_id],
            [
                'last_active_date' => now()->subDays(2)->toDateString(),
                'remarks' => $request->remarks,
                'contact_status' => $request->contact_status ?? 'Contacted',
                'follow_up_date' => now()->toDateString(),
            ]
        );

        return response()->json(['success' => true, 'followUp' => $followUp]);
    }
}
