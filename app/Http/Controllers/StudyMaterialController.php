<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\StudyMaterial;
use Illuminate\Support\Facades\Storage;

class StudyMaterialController extends Controller
{
    public function index(Request $request)
    {
        $courseId = $request->query('course_id');
        $query = StudyMaterial::query();
        if ($courseId) {
            $query->where('course_id', $courseId);
        }
        return response()->json($query->latest()->get());
    }

    public function upload(Request $request)
    {
        $request->validate([
            'course_id' => 'required|string',
            'title' => 'required|string',
            'file' => 'required|file|max:10240',
        ]);

        $path = $request->file('file')->store('materials', 'public');

        $material = StudyMaterial::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'file_type' => $request->file('file')->getClientOriginalExtension(),
            'file_path' => $path,
        ]);

        return response()->json($material, 201);
    }

    public function download($id)
    {
        $material = StudyMaterial::findOrFail($id);
        return Storage::disk('public')->download($material->file_path, $material->title . '.' . $material->file_type);
    }
}
