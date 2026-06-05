<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FacultyDashboardController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'assigned_courses' => [
                ['id' => 'fc-1', 'title' => 'Advanced React Patterns', 'students' => 124],
                ['id' => 'fc-2', 'title' => 'Fullstack Laravel', 'students' => 89]
            ],
            'today_classes' => [
                ['id' => 'tc-1', 'time' => '10:00 AM', 'topic' => 'State Management', 'course' => 'Advanced React Patterns']
            ],
            'pending_reviews' => 12
        ]);
    }
}
