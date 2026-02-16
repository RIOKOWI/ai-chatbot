<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . "?key=" . $this->apiKey, [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $request->post('content')]
                    ]
                ]
            ],
            "generationConfig" => [
                "temperature" => 0,
                "maxOutputTokens" => 2048,
            ]
        ]);

        if ($response->failed()) {
            return response()->json([
                'error' => 'Gemini API Error',
                'details' => $response->json()
            ], $response->status());
        }

        $data = $response->json();
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response';

        return response()->json([
            'answer' => $text
        ]);

        Log::info(json_encode($response));
        Log::info(json_encode($text));
    }
}
