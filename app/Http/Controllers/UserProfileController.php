<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserProfile;
use App\Models\User;

class UserProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string',
            'bio' => 'nullable|string',
            'github_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
            $user->save();
        }

        $profile = UserProfile::firstOrCreate(['user_id' => $user->id]);
        if ($request->has('phone')) $profile->phone = $request->input('phone');
        if ($request->has('bio')) $profile->bio = $request->input('bio');
        if ($request->has('github_url')) $profile->github_url = $request->input('github_url');
        if ($request->has('linkedin_url')) $profile->linkedin_url = $request->input('linkedin_url');
        
        $profile->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }

    public function optimizeLinkedIn(Request $request)
    {
        $targetRole = $request->input('targetRole', 'Software Engineer');

        return response()->json([
            'headline' => "🚀 Aspiring {$targetRole} | React 19 & Node.js Specialist | Building Scalable Web Apps",
            'aboutSummary' => "Passionate full-stack developer specializing in modern web architecture, responsive UI with Tailwind, and high-performance Express REST APIs. Currently honing technical skills on AceNext AI Learning Platform with hands-on labs and algorithm challenges.",
            'seoKeywords' => ['React 19', 'Node.js', 'Express API', 'Tailwind CSS', 'TypeScript', 'RESTful Design', 'SQL'],
            'suggestedSkills' => ['Web Architecture', 'Frontend Development', 'Database Systems', 'Version Control (Git)'],
            'improvementTips' => [
                "Include quantitative metrics (e.g. 'Optimized render cycles by 25%')",
                "Add direct links to your AceNext GitHub portfolio projects",
                "Set open-to-work visibility for tech recruiters"
            ]
        ]);
    }
}
