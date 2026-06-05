<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AiTutorController extends Controller
{
    public function chat(Request $request)
    {
        // 1. Strict Payload Validation (Security)
        $request->validate([
            'messages' => 'required|array|max:50', // Max 50 messages to prevent memory exhaustion
            'messages.*.role' => 'required|in:user,model,assistant',
            'messages.*.content' => 'required|string|max:4000', // Limit content length
            'context' => 'nullable|array'
        ]);

        $user = $request->user();
        $messages = $request->input('messages');

        $lastUserMessage = end($messages);
        if (!$lastUserMessage || $lastUserMessage['role'] !== 'user') {
            return response()->json(['content' => 'Please provide a valid question.'], 400);
        }

        // 2. Get or create a ChatSession
        $session = ChatSession::firstOrCreate(
            ['user_id' => $user->id],
            ['session_token' => uniqid('sess_')]
        );

        // 3. Save user message & sanitize
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'role' => 'user',
            'content' => strip_tags($lastUserMessage['content']) 
        ]);

        // 4. Prepare Gemini API for SSE
        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:streamGenerateContent?alt=sse&key={$apiKey}";

        $contents = [];
        foreach ($messages as $msg) {
            $contents[] = [
                'role' => $msg['role'] === 'model' ? 'model' : 'user',
                'parts' => [['text' => strip_tags($msg['content'])]]
            ];
        }

        $payload = json_encode(['contents' => $contents]);

        // 5. Return StreamedResponse
        return new StreamedResponse(function () use ($url, $payload, $session) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
            
            $fullAiResponse = '';

            curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) use (&$fullAiResponse) {
                // Accumulate text for database saving
                $lines = explode("\n", $data);
                foreach ($lines as $line) {
                    if (str_starts_with($line, 'data: ')) {
                        $jsonString = substr($line, 6);
                        if (trim($jsonString) !== '[DONE]' && !empty(trim($jsonString))) {
                            $decoded = json_decode($jsonString, true);
                            if (isset($decoded['candidates'][0]['content']['parts'][0]['text'])) {
                                $fullAiResponse .= $decoded['candidates'][0]['content']['parts'][0]['text'];
                            }
                        }
                    }
                }

                // Proxy the exact SSE stream to the frontend
                echo $data;
                
                if (ob_get_level() > 0) ob_flush();
                flush();
                
                return strlen($data);
            });

            curl_exec($ch);
            
            if (curl_errno($ch)) {
                Log::error('Gemini API Curl Error: ' . curl_error($ch));
            }
            
            curl_close($ch);

            // 6. Save the complete AI message to the DB
            if (!empty($fullAiResponse)) {
                ChatMessage::create([
                    'chat_session_id' => $session->id,
                    'role' => 'assistant',
                    'content' => $fullAiResponse
                ]);
            }

        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no', // Important for streaming proxies
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, Accept'
        ]);
    }
}
