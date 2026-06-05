<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assessment;
use App\Models\Question;
use App\Models\Option;
use App\Models\CourseModule;

class PracticeSeeder extends Seeder
{
    public function run(): void
    {
        $module = CourseModule::first();

        $assessment = Assessment::create([
            'course_id' => $module->course_id ?? null,
            'title' => 'React Fundamentals Quiz',
            'duration_mins' => 15,
            'passing_score' => 70,
        ]);

        $q1 = Question::create([
            'assessment_id' => $assessment->id,
            'content' => 'What is a React component?',
            'type' => 'mcq',
            'metadata' => ['points' => 10],
        ]);

        Option::create(['question_id' => $q1->id, 'option_text' => 'A UI builder', 'is_correct' => false]);
        Option::create(['question_id' => $q1->id, 'option_text' => 'A reusable piece of UI', 'is_correct' => true]);

        $q2 = Question::create([
            'assessment_id' => $assessment->id,
            'content' => 'What hook is used for side effects?',
            'type' => 'mcq',
            'metadata' => ['points' => 10],
        ]);

        Option::create(['question_id' => $q2->id, 'option_text' => 'useState', 'is_correct' => false]);
        Option::create(['question_id' => $q2->id, 'option_text' => 'useEffect', 'is_correct' => true]);
    }
}
