<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExtractionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'changePassword'])->name('profile.password');

    // Account routes
    Route::get('/account', [App\Http\Controllers\AccountController::class, 'index'])->name('account.index');
    Route::put('/account/password', [App\Http\Controllers\AccountController::class, 'updatePassword'])->name('account.password');
    Route::put('/account/email', [App\Http\Controllers\AccountController::class, 'updateEmail'])->name('account.email');
    Route::delete('/account', [App\Http\Controllers\AccountController::class, 'deleteAccount'])->name('account.delete');

    // Extraction routes
    Route::get('/extraction', [ExtractionController::class, 'index'])->name('extraction.index');
    Route::post('/extraction/export', [ExtractionController::class, 'exportExcel'])->name('extraction.export');
    Route::get('/extraction/test-export', [ExtractionController::class, 'testExport'])->name('extraction.test-export');
    Route::get('/extraction/debug', function() {
        return response()->json([
            'storage_path' => storage_path('app/temp'),
            'temp_dir' => sys_get_temp_dir(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version()
        ]);
    });
});
