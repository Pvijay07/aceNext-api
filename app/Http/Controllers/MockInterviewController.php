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
        $request->validate([
            'role' => 'required|string',
            'level' => 'required|string',
            'mode' => 'required|string',
            'history' => 'array',
            'currentAnswer' => 'required|string'
        ]);

        $apiKey = env('GEMINI_API_KEY');

        $systemInstruction = "You are an expert AI Interviewer for a {$request->level} {$request->role} role. The focus mode is {$request->mode}. " .
            "Your job is to ask ONE relevant interview question at a time. " .
            "If the user provides an answer, you must evaluate it, giving a score (0-100), short feedback, strengths (array of strings), and improvements (array of strings), AND then ask the next question. " .
            "If this is the start of the interview, skip the evaluation and just ask the first question. " .
            "You MUST return your response strictly as a JSON object with this exact schema: " .
            "{ \"nextQuestion\": \"string\", \"evaluation\": { \"score\": number, \"feedback\": \"string\", \"strengths\": [\"string\"], \"improvements\": [\"string\"] } }. " .
            "If there is no evaluation, omit the \"evaluation\" key. Do not use markdown blocks like ```json.";

        $contents = [];
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $systemInstruction]]
        ];
        $contents[] = [
            'role' => 'model',
            'parts' => [['text' => "Understood. I will strictly output JSON."]]
        ];

        if ($request->has('history') && is_array($request->history)) {
            foreach ($request->history as $msg) {
                $contents[] = [
                    'role' => $msg['role'] === 'model' ? 'model' : 'user',
                    'parts' => [['text' => $msg['content'] ?? '']]
                ];
            }
        }

        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $request->currentAnswer]]
        ];

        try {
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

                if (!$jsonResponse) {
                    Log::error("Failed to parse Gemini output", ['output' => $geminiText]);
                    return response()->json(['nextQuestion' => "I apologize, but I encountered an error processing your response. Could you rephrase it?"]);
                }

                return response()->json($jsonResponse);
            }

            return response()->json(['error' => 'Failed to reach Gemini API'], 500);
        } catch (\Exception $e) {
            Log::error('Gemini Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
