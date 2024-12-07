<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlShortenerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/encode', [UrlShortenerController::class, 'encode']);
Route::post('/decode', [UrlShortenerController::class, 'decode']);
