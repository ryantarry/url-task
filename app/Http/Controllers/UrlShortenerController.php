<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class UrlShortenerController extends Controller
{

    public function encode(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $originalUrl = $request->input('url');

        $urlCode = Str::random(8);

        Cache::put($urlCode, $originalUrl, 120 * 60);

        $shortUrl = url($urlCode);
        return response()->json([
            'short_url' => $shortUrl
        ]);
    }


    public function decode(Request $request)
    {
        $request->validate([
            'short_url' => 'required|url'
        ]);

        $shortUrl = $request->input('short_url');
        $urlCode = basename($shortUrl);

        $originalUrl = Cache::get($urlCode);

        if (!$originalUrl) {
            return response()->json(['error' => 'URL not found'], 404);
        }

        return response()->json([
            'original_url' => $originalUrl
        ]);
    }
}
