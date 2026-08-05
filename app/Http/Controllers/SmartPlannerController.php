<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SmartPlannerController extends Controller
{
    public function generate(Request $request)
    {
        $availability = $request->input('availability', []);
        $deadlines = $request->input('deadlines', []);

        $dailyHoursText = ($availability['dailyHours'] ?? 2) . " hours";
        $prefTimeText = $availability['preferredTime'] ?? "evening";

        $schedule = [
            [
                'day' => 'Monday',
                'focus' => 'Foundation Theory & Concept Warmup',
                'activities' => [
                    "Study active core chapters of '" . ($deadlines[0]['courseTitle'] ?? "React Ecosystem") . "'",
                    "Review fundamental notes on current challenge: '" . ($deadlines[0]['task'] ?? "State rendering") . "'"
                ],
                'duration' => $dailyHoursText,
                'tips' => "Optimal session time: during the {$prefTimeText}. Spend 15 minutes on terminology."
            ],
            [
                'day' => 'Tuesday',
                'focus' => 'Coding Arena Problem Practice',
                'activities' => [
                    "Launch AceNext Coding Arena and warm up",
                    "Solve complement indexes finders or sliding window challenges"
                ],
                'duration' => $dailyHoursText,
                'tips' => "Read the prompt carefully, layout visual comments first, then write tests."
            ],
            [
                'day' => 'Wednesday',
                'focus' => 'API Middleware & Endpoint Setup',
                'activities' => [
                    "Deep-dive into '" . ($deadlines[1]['courseTitle'] ?? "Express Server") . "' modules",
                    "Set up custom router structures and debug security headers"
                ],
                'duration' => $dailyHoursText,
                'tips' => "Review error handlers and check if all CORS constraints function correctly."
            ],
            [
                'day' => 'Thursday',
                'focus' => 'Assessment Quizzes & Evaluation Check',
                'activities' => [
                    "Solve 1 full practice assessment set",
                    "Generate dynamic AI assessment quizzes for difficult domains"
                ],
                'duration' => $dailyHoursText,
                'tips' => "Review detailed logic explanations for incorrect choices and take notes."
            ],
            [
                'day' => 'Friday',
                'focus' => 'Syllabus Milestones Polish',
                'activities' => [
                    "Commit polish edits for immediate deadline: '" . ($deadlines[0]['task'] ?? "React 19 & Tailwind") . "'",
                    "Optimize resume bullet list using quantifiable metrics and action-verbs"
                ],
                'duration' => $dailyHoursText,
                'tips' => "Avoid rushing through complex algorithms. High completion rate builds retention."
            ],
            [
                'day' => 'Saturday',
                'focus' => 'Durable Project Sandbox Lab',
                'activities' => [
                    "Attempt advanced modules in the Project Labs Panel",
                    "Write standard testing logs to ensure boundaries don't crash"
                ],
                'duration' => "Extended session (3 hours)",
                'tips' => "Tackle high-difficulty problems during your peak cognitive energy block."
            ],
            [
                'day' => 'Sunday',
                'focus' => 'Structured Review & Career Strategy',
                'activities' => [
                    "Launch a Mock Interview Session to gauge your vocal delivery competence",
                    "Unlock consistency heatmap nodes and log daily stats"
                ],
                'duration' => "1.0 hours",
                'tips' => "Perform a light retrospect on major struggles and configure next week's availability."
            ]
        ];

        return response()->json([
            'schedule' => $schedule,
            'recommendations' => [
                "You indicated a preference for {$prefTimeText} slots. Structure your study environment 15 mins prior.",
                "Keep your immediate deadline '" . ($deadlines[0]['task'] ?? "React 19 & Tailwind") . "' in sight.",
                "Active AI Schedule: Generated dynamically by AceNext AI Learning Platform."
            ]
        ]);
    }
}
