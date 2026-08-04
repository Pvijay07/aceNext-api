<?php

namespace App\Http\Controllers;

use App\Models\CounselingSession;
use Illuminate\Http\Request;

class CounselingController extends Controller
{
    public function index()
    {
        $sessions = CounselingSession::with('student:id,name,email')->get();
        return response()->json($sessions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'session_date' => 'required|date',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $session = CounselingSession::create($request->all());

        return response()->json($session->load('student:id,name,email'), 201);
    }

    public function update(Request $request, $id)
    {
        $session = CounselingSession::findOrFail($id);
        $session->update($request->all());

        return response()->json($session->load('student:id,name,email'));
    }

    public function destroy($id)
    {
        $session = CounselingSession::findOrFail($id);
        $session->delete();

        return response()->json(['success' => true]);
    }
}
