<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MockInterviewController extends Controller
{
    public function generate(Request $request)
    {
        $role = $request->input('role', 'frontend');
        $level = $request->input('level', 'Senior');
        $mode = $request->input('mode', 'Technical');
        $history = $request->input('history', []);
        $currentAnswer = $request->input('currentAnswer', '');

        $mockQuestions = [
            'frontend' => [
                "What is the difference between state and props in React?",
                "How does React's virtual DOM reconciliation algorithm work under the hood?",
                "Can you explain JavaScript closures and provide a common use case?",
                "Explain the CSS Box Model and differences between border-box and content-box."
            ],
            'backend' => [
                "What are the key benefits of using transaction isolation levels in databases?",
                "Explain server-side vs client-side caching strategies and how Redis fits into high-volume backends.",
                "How do you handle authentication securely in Node.js/Express REST APIs?",
                "Describe how you would design a rate-limiting middleware for API gateways."
            ],
            'pm' => [
                "How would you prioritize product features for a new app?",
                "Describe a time you had to make a product tradeoff due to tight developer timeline constraints."
            ]
        ];

        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            $selectedList = $mockQuestions[$role] ?? $mockQuestions['frontend'];
            $questionIndex = count($history);
            $nextQuestion = $selectedList[$questionIndex % count($selectedList)];

            $feedback = "Excellent answer. Try using more precise developer keywords in your response!";
            $score = strlen($currentAnswer) > 5 ? rand(78, 95) : 85;

            return response()->json([
                'nextQuestion' => $nextQuestion,
                'evaluation' => [
                    'score' => $score,
                    'feedback' => $feedback,
                    'strengths' => ["Clear response structure", "Good conceptual clarity"],
                    'improvements' => [
                        "Use concrete metrics to scale your engineering scenarios",
                        "Consider structural memory constraints in explanations"
                    ]
                ]
            ]);
        }

        try {
            $systemInstruction = "You are an expert AI Interviewer for a {$level} {$role} role ({$mode}). " .
                "Your job is to ask ONE relevant interview question at a time. " .
                "Evaluate the candidate's answer and output strictly JSON: " .
                "{ \"nextQuestion\": \"string\", \"evaluation\": { \"score\": 85, \"feedback\": \"string\", \"strengths\": [\"string\"], \"improvements\": [\"string\"] } }";

            $contents = [
                ['role' => 'user', 'parts' => [['text' => $systemInstruction]]],
                ['role' => 'model', 'parts' => [['text' => "Understood."]]]
            ];

            if (is_array($history)) {
                foreach ($history as $msg) {
                    $contents[] = [
                        'role' => ($msg['role'] ?? '') === 'model' ? 'model' : 'user',
                        'parts' => [['text' => $msg['content'] ?? '']]
                    ];
                }
            }

            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $currentAnswer ?: "Hello, I am ready for the interview."]]
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey, [
                'contents' => $contents
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $geminiText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
                $geminiText = str_replace(['```json', '```'], '', $geminiText);
                $jsonResponse = json_decode(trim($geminiText), true);

                if ($jsonResponse) {
                    return response()->json($jsonResponse);
                }
            }
        } catch (\Exception $e) {
            Log::error('Gemini Exception: ' . $e->getMessage());
        }

        // Fallback response if Gemini API key fails
        $selectedList = $mockQuestions[$role] ?? $mockQuestions['frontend'];
        $nextQuestion = $selectedList[count($history) % count($selectedList)];

        return response()->json([
            'nextQuestion' => $nextQuestion,
            'evaluation' => [
                'score' => 85,
                'feedback' => "Good response. Elaborate more on time complexity in your next answer.",
                'strengths' => ["Declarative communication", "Solid concepts"],
                'improvements' => ["Elaborate on edge case validations"]
            ]
        ]);
    }
}
