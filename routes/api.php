<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrScannerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public scanner API (no CSRF required)
Route::post('/scanner/scan', [QrScannerController::class, 'scanPublic']);