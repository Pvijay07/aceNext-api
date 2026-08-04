<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        // Get all live classes and their attendance
        $attendances = DB::table('class_attendances')
            ->join('users', 'class_attendances.user_id', '=', 'users.id')
            ->join('live_classes', 'class_attendances.live_class_id', '=', 'live_classes.id')
            ->select(
                'class_attendances.id',
                'class_attendances.status',
                'class_attendances.join_time',
                'users.name as student_name',
                'users.email as student_email',
                'live_classes.title as class_title',
                'live_classes.scheduled_at as class_date'
            )
            ->get();

        return response()->json($attendances);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        DB::table('class_attendances')
            ->where('id', $id)
            ->update(['status' => $request->status, 'updated_at' => now()]);

        return response()->json(['success' => true]);
    }
}
