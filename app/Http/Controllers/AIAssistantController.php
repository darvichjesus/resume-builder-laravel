<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIAssistantController extends Controller
{
    public function suggest(Request $request)
    {
        $request->validate([
            'text' => 'required|string|min:10',
            'context' => 'required|string'
        ]);

        $apiKey = env('OPENAI_API_KEY');
        if (!$apiKey) {
            return response()->json(['error' => 'API Key not configured. Please add OPENAI_API_KEY to your .env file.'], 400);
        }

        $prompt = "You are an expert HR resume writer. Improve the following {$request->context} to be more professional, impactful, and ATS-friendly. Keep the response in the exact same language as the input. Respond ONLY with the improved text, no quotes or meta-commentary.\n\nOriginal Text: {$request->text}";

        try {
            $response = Http::withToken($apiKey)
                ->timeout(15)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-3.5-turbo', // Default fast model
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                $suggestion = $response->json('choices.0.message.content');
                $suggestion = trim($suggestion, "\"' \n"); // Clenup quotes
                return response()->json(['suggestion' => $suggestion]);
            }

            return response()->json(['error' => 'Failed to get response from AI. Check your API key limit.'], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
