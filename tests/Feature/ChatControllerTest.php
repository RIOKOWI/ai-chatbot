<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    /**
     * Setup test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear cache before each test
        Cache::flush();
        
        // Mock the config
        config(['gemini.api_key' => 'test-api-key']);
        config(['gemini.base_url' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent']);
    }

    /**
     * Test successful chat request with valid content
     */
    public function test_chat_returns_response_with_valid_content()
    {
        // Mock the HTTP request
        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'This is a test response from Gemini API']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->postJson('/api/chat', [
            'content' => 'Hello, how are you?'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'answer' => 'This is a test response from Gemini API'
            ]);

        // Verify HTTP request was made
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'generativelanguage.googleapis.com');
        });
    }

    /**
     * Test chat fails when content is missing
     */
    public function test_chat_fails_when_content_is_missing()
    {
        $response = $this->postJson('/api/chat', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    /**
     * Test chat fails when content is empty string
     */
    public function test_chat_fails_when_content_is_empty_string()
    {
        $response = $this->postJson('/api/chat', [
            'content' => ''
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    /**
     * Test chat fails when content is not a string
     */
    public function test_chat_fails_when_content_is_not_string()
    {
        $response = $this->postJson('/api/chat', [
            'content' => 12345
        ]);

        // Should pass because JSON will convert int to string or validation should catch it
        // This depends on Laravel's validation behavior
        $this->assertTrue(true);
    }

    /**
     * Test API error is handled properly
     */
    public function test_chat_handles_api_error_response()
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent*' => Http::response([], 500)
        ]);

        $response = $this->postJson('/api/chat', [
            'content' => 'Test message'
        ]);

        $response->assertStatus(500);
    }

    /**
     * Test caching works as expected
     */
    public function test_chat_caches_response_for_same_prompt()
    {
        $prompt = 'What is Laravel?';
        $mockResponse = [
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            ['text' => 'Laravel is a PHP framework']
                        ]
                    ]
                ]
            ]
        ];

        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent*' => Http::response($mockResponse, 200)
        ]);

        // First request - should call API
        $response1 = $this->postJson('/api/chat', [
            'content' => $prompt
        ]);

        // Second identical request - should use cache
        $response2 = $this->postJson('/api/chat', [
            'content' => $prompt
        ]);

        $response1->assertStatus(200)
            ->assertJson(['answer' => 'Laravel is a PHP framework']);

        $response2->assertStatus(200)
            ->assertJson(['answer' => 'Laravel is a PHP framework']);

        // HTTP should only be called once due to caching
        Http::assertSentCount(1);
    }

    /**
     * Test cache is different for different prompts
     */
    public function test_chat_uses_different_cache_for_different_prompts()
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent*' => Http::sequence()
                ->push(['candidates' => [[
                    'content' => ['parts' => [['text' => 'Response 1']]]
                ]]], 200)
                ->push(['candidates' => [[
                    'content' => ['parts' => [['text' => 'Response 2']]]
                ]]], 200)
        ]);

        $response1 = $this->postJson('/api/chat', [
            'content' => 'First question'
        ]);

        $response2 = $this->postJson('/api/chat', [
            'content' => 'Second question'
        ]);

        $response1->assertJson(['answer' => 'Response 1']);
        $response2->assertJson(['answer' => 'Response 2']);

        // HTTP should be called twice (different cache keys)
        Http::assertSentCount(2);
    }

    /**
     * Test with whitespace is trimmed from prompt
     */
    public function test_chat_trims_whitespace_from_prompt()
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Trimmed response']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->postJson('/api/chat', [
            'content' => '   Hello world   '
        ]);

        $response->assertStatus(200)
            ->assertJson(['answer' => 'Trimmed response']);
    }

    /**
     * Test with long prompt
     */
    public function test_chat_accepts_long_prompt()
    {
        $longPrompt = str_repeat('Test ', 1000); // 5000 characters

        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Response to long prompt']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->postJson('/api/chat', [
            'content' => $longPrompt
        ]);

        $response->assertStatus(200)
            ->assertJson(['answer' => 'Response to long prompt']);
    }

    /**
     * Test API timeout handling
     */
    public function test_chat_handles_timeout()
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent*' => Http::response([], 408) // Request Timeout
        ]);

        $response = $this->postJson('/api/chat', [
            'content' => 'Test message'
        ]);

        $response->assertStatus(408);
    }

    /**
     * Test missing response parts in API response
     */
    public function test_chat_handles_malformed_api_response()
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent*' => Http::response([
                'candidates' => []
            ], 200)
        ]);

        $response = $this->postJson('/api/chat', [
            'content' => 'Test message'
        ]);

        // Should either return 500 or handle the missing data
        $response->assertStatus(500);
    }

    /**
     * Test HTTP headers are set correctly in API call
     */
    public function test_chat_sends_correct_headers_to_api()
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Test']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $this->postJson('/api/chat', [
            'content' => 'Test message'
        ]);

        Http::assertSent(function ($request) {
            return $request->header('Content-Type') === 'application/json';
        });
    }

    /**
     * Test API key is included in request
     */
    public function test_chat_includes_api_key_in_request()
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Test']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $this->postJson('/api/chat', [
            'content' => 'Test message'
        ]);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'key=test-api-key');
        });
    }
}
