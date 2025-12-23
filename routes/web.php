<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardStatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\QrScannerController;
use App\Http\Controllers\RentalController;

// Landing page (public)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Dashboard (authenticated users)
Route::get('/home', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('home');
// Keep a /dashboard path but name it `dashboard.index` so older redirects/controllers
// that reference `route('dashboard.index')` continue to work.
Route::get('/dashboard', function() { return redirect()->route('home'); })->name('dashboard.index');

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Stats CRUD (kept for backward compatibility)
    Route::resource('dashboard-stats', DashboardStatController::class);
    
    // QR Scanner (untuk semua user)
    Route::get('/scanner', [QrScannerController::class, 'index'])->name('scanner.index');
    Route::post('/scanner/scan', [QrScannerController::class, 'scan'])->name('scanner.scan');
    
    // Rental Management
    Route::get('/rental', [RentalController::class, 'index'])->name('rental.page');
    Route::post('/rental/extend', [RentalController::class, 'extend'])->name('rental.extend');
    
    // Admin only
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('barcode', BarcodeController::class);
        Route::resource('rooms', RoomController::class);
        Route::get('/rooms/{room}/print-qr', [RoomController::class, 'printQrCode'])->name('rooms.print-qr');
        Route::get('/rooms/{room}/download-qr', [RoomController::class, 'downloadQrCode'])->name('rooms.download-qr');
        Route::get('/room-view', [RoomController::class, 'roomView'])->name('rooms.view');
    });

    // Send barcode link to user (admin)
    Route::post('/barcode/{user}/send-link', [BarcodeController::class, 'generateAndSend'])->name('barcode.send-link');
    
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Public route for downloading barcode images via signed URL
Route::get('/barcode/download/{user}/{file}', [BarcodeController::class, 'download'])->name('barcode.download');

// Public scanner route (no CSRF)
Route::post('/api/scanner/scan', [QrScannerController::class, 'scanPublic'])->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);

// Test route
Route::get('/api/test', function() {
    return response()->json(['status' => 'API working', 'time' => now()]);
});

// Test scanner page
Route::get('/test-scanner', function() {
    return view('test-scanner');
})->middleware('auth');


