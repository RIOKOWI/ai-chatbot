<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('gemini.api_key');
        $this->baseUrl = config('gemini.base_url');
    }

    public function __invoke(Request $request)
    {

        $request->validate([
            'content' => 'required|string'
        ]);

        $prompt = trim($request->post('content'));

        $cacheKey = 'gemini_' . md5($prompt);

        $answer = Cache::remember($cacheKey, now()->addDay(7), function () use ($prompt) {

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])
        ->timeout(60)
        ->connectTimeout(15)
        ->post($this->baseUrl . "?key=" . $this->apiKey, [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ],
            "generationConfig" => [
                "temperature" => 0,
                "maxOutputTokens" => 2048,
            ]
        ]);

        if ($response->failed()) {
            throw new \Exception("Gemini API Error");
        }

        $data = $response->json();

        Log::info(json_encode($response));
        Log::info(json_encode($data));
        
        return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response';
    });
    return response()->json([
        'answer' => $answer
    ]);
    }
}
