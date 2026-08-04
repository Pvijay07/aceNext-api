<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Batch;

class FacultyController extends Controller
{
    public function index()
    {
        $faculty = User::where('role_id', 2)->get()->map(function ($f) {
            $workloads = Batch::where('faculty_id', $f->id)->pluck('name')->toArray();
            return [
                'id' => $f->id,
                'name' => $f->name,
                'email' => $f->email,
                'phone' => $f->phone ?? '—',
                'qualification' => $f->qualification ?? '—',
                'specialization' => $f->specialization ?? '—',
                'experience' => $f->experience ?? '—',
                'status' => $f->status,
                'avatar_url' => $f->avatar_url ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150',
                'workload' => $workloads,
            ];
        });

        return response()->json($faculty);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string',
            'qualification' => 'nullable|string',
            'specialization' => 'nullable|string',
            'experience' => 'nullable|string',
        ]);

        $faculty = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make('password'),
            'role_id' => 2, // faculty
            'status' => 'active',
            'phone' => $request->phone,
            'qualification' => $request->qualification,
            'specialization' => $request->specialization,
            'experience' => $request->experience,
        ]);

        return response()->json($faculty, 201);
    }

    public function update(Request $request, $id)
    {
        $faculty = User::findOrFail($id);
        $faculty->update($request->only([
            'name', 'email', 'phone', 'qualification', 'specialization', 'experience', 'status'
        ]));

        return response()->json($faculty);
    }
}
