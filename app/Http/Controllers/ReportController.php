<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Batch;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $totalStudents = User::whereHas('role', function($q) { $q->where('name', 'student'); })->count();
        $totalBatches = Batch::count();
        $avgAttendance = DB::table('class_attendances')->where('status', 'Present')->count();
        $totalClasses = DB::table('class_attendances')->count();

        $attendanceRate = $totalClasses > 0 ? round(($avgAttendance / $totalClasses) * 100) : 85;

        // Mocking some extra data for reports
        $recentActivity = [
            ['id' => 1, 'type' => 'Assessment', 'student' => 'Amit Verma', 'score' => '85%', 'date' => 'Today'],
            ['id' => 2, 'type' => 'Project', 'student' => 'Priya Singh', 'score' => 'Pending Review', 'date' => 'Yesterday'],
        ];

        return response()->json([
            'summary' => [
                'total_students' => $totalStudents,
                'total_batches' => $totalBatches,
                'attendance_rate' => $attendanceRate,
                'avg_score' => 78,
            ],
            'recent_activity' => $recentActivity
        ]);
    }
}
