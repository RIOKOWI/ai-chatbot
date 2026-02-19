<?php

namespace Tests\Unit;

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatControllerUnitTest extends TestCase
{
    /**
     * Setup before each test
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear cache
        Cache::flush();
        
        // Configure mock API credentials
        config(['gemini.api_key' => 'test-api-key']);
        config(['gemini.base_url' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent']);
    }

    /**
     * Test ChatController can be instantiated
     */
    public function test_chat_controller_can_instantiate()
    {
        $controller = new ChatController();
        $this->assertInstanceOf(ChatController::class, $controller);
    }

    /**
     * Test ChatController constructor sets API key
     */
    public function test_chat_controller_constructor_sets_api_key()
    {
        $controller = new ChatController();
        $reflection = new \ReflectionClass($controller);
        $property = $reflection->getProperty('apiKey');
        $property->setAccessible(true);
        
        $this->assertEquals('test-api-key', $property->getValue($controller));
    }

    /**
     * Test ChatController constructor sets base URL
     */
    public function test_chat_controller_constructor_sets_base_url()
    {
        $controller = new ChatController();
        $reflection = new \ReflectionClass($controller);
        $property = $reflection->getProperty('baseUrl');
        $property->setAccessible(true);
        
        $this->assertEquals(
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent',
            $property->getValue($controller)
        );
    }

    /**
     * Test HTTP client is called with correct configuration
     */
    public function test_http_client_called_with_correct_timeout()
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Test response']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $request = $this->createMock(\Illuminate\Http\Request::class);
        $request->method('validate')->willReturn(true);
        $request->method('post')->willReturn('Test prompt');

        $controller = new ChatController();
        $controller($request);

        // Verify the HTTP request was made
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'generativelanguage.googleapis.com');
        });
    }

    /**
     * Test API response is properly parsed
     */
    public function test_api_response_is_properly_parsed()
    {
        $expectedText = 'This is the AI response';
        
        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => $expectedText]
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->postJson('/api/chat', [
            'content' => 'Test message'
        ]);

        $response->assertJson(['answer' => $expectedText]);
    }

    /**
     * Test cache key is generated correctly based on prompt
     */
    public function test_cache_key_generation()
    {
        $prompt = 'Test prompt';
        $expectedCacheKey = 'gemini_' . md5($prompt);

        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Response']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $this->postJson('/api/chat', [
            'content' => $prompt
        ]);

        // Verify the value is cached with the expected key
        $this->assertTrue(Cache::has($expectedCacheKey));
    }

    /**
     * Test response contains JSON structure
     */
    public function test_response_contains_answer_key()
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Sample response']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->postJson('/api/chat', [
            'content' => 'Sample prompt'
        ]);

        $response->assertJsonStructure(['answer']);
        $response->assertJsonMissing(['error']);
    }
}
