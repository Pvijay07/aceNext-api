<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserProfile;

class GamificationController extends Controller
{
    public function syncProgress(Request $request)
    {
        $profile = UserProfile::firstOrCreate(['user_id' => $request->user()->id]);
        
        $lessonId = $request->input('lessonId');
        $quizId = $request->input('quizId');
        $score = $request->input('score');
        $labId = $request->input('labId');
        $xpGained = $request->input('xpGained', 0);

        if ($lessonId) {
            $lessons = $profile->completed_lessons ?? [];
            if (!in_array($lessonId, $lessons)) {
                $lessons[] = $lessonId;
                $profile->completed_lessons = $lessons;
            }
        }

        if ($labId) {
            $labs = $profile->completed_labs ?? [];
            if (!in_array($labId, $labs)) {
                $labs[] = $labId;
                $profile->completed_labs = $labs;
            }
        }

        if ($quizId && $score !== null) {
            $quizzes = $profile->completed_quizzes ?? [];
            $updated = false;
            foreach ($quizzes as &$q) {
                if (isset($q['id']) && $q['id'] === $quizId) {
                    $q['score'] = max($q['score'], $score);
                    $updated = true;
                    break;
                }
            }
            if (!$updated) {
                $quizzes[] = ['id' => $quizId, 'score' => $score];
            }
            $profile->completed_quizzes = $quizzes;
        }

        $profile->xp += $xpGained;
        
        $badges = $profile->badges ?? [];
        if ($profile->xp >= 1800 && !collect($badges)->contains('id', 'b4')) {
            $badges[] = [
                'id' => 'b4',
                'title' => 'Elite Scholar',
                'icon' => 'ShieldAlert',
                'desc' => 'Exceeded 1,800 total lifetime study experience points',
                'color' => 'text-purple-500 bg-purple-50'
            ];
        }
        $profile->badges = $badges;

        $profile->save();

        return response()->json(['profile' => $profile]);
    }
}
