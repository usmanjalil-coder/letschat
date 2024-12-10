<?php

use App\Http\Controllers\RecordingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/recordings-post', [RecordingController::class, 'store'])->name('recordings.store');

