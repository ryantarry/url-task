<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UrlShortenerTest extends TestCase
{

    public function testEncodeValidUrl()
    {
        Cache::shouldReceive('put')
            ->once()
            ->withArgs(function ($key, $value, $ttl) {
                return Str::length($key) === 8 && filter_var($value, FILTER_VALIDATE_URL) && $ttl === 120 * 60;
            });

        $response = $this->postJson('api/encode', [
            'url' => 'https://www.example.com',
        ]);


        $response->assertStatus(200)
                ->assertJsonStructure(['short_url']);

        $shortUrl = $response->json('short_url');
        $this->assertStringStartsWith(url('/'), $shortUrl);
        $this->assertEquals(8, Str::length(basename($shortUrl)));
    }


    public function testEncodeInvalidUrl()
    {
        $response = $this->postJson('api/encode', [
            'url' => 'invalid-url',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['url']);
    }


    public function testDecodeValidShortUrl()
    {
        $urlCode = Str::random(8);
        $originalUrl = 'https://www.example.com';
        Cache::put($urlCode, $originalUrl, 120 * 60);
        $shortUrl = url($urlCode);

        $response = $this->postJson('api/decode', [
            'short_url' => $shortUrl,
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'original_url' => $originalUrl,
                ]);
    }

    public function testDecodeInvalidShortUrl()
    {
        $response = $this->postJson('api/decode', [
            'short_url' => 'invalid-short-url',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['short_url']);
    }

    public function testDecodeNonexistentShortUrl()
    {
        $urlCode = Str::random(8);
        $shortUrl = url($urlCode);

        $this->assertFalse(Cache::has($urlCode));

        $response = $this->postJson('api/decode', [
            'short_url' => $shortUrl,
        ]);

        $response->assertStatus(404)->assertJson([
                    'error' => 'URL not found',
                ]);
    }
}
