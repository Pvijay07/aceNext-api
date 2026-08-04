<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Batch;
use App\Models\JobApplication;

class CoordinatorController extends Controller
{
    public function dashboard()
    {
        $totalStudents = User::where('role_id', 3)->count();
        $totalFaculty = User::where('role_id', 2)->count();
        $totalBatches = Batch::count();
        
        $placedCount = JobApplication::whereIn('status', ['Placed', 'Joined', 'Selected'])->count();
        $placementPercentage = $totalStudents > 0 ? round(($placedCount / $totalStudents) * 100) : 0;

        $inactiveCount = User::where('role_id', 3)->where('status', 'inactive')->count();
        $delayedBatches = Batch::where('status', 'Delayed')->count();

        return response()->json([
            'total_students' => $totalStudents,
            'total_faculty' => $totalFaculty,
            'total_batches' => $totalBatches,
            'placed_count' => $placedCount,
            'placement_percentage' => $placementPercentage,
            'inactive_count' => $inactiveCount,
            'delayed_batches' => $delayedBatches
        ]);
    }
}
