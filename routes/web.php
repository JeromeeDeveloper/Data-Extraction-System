<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExtractionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConfigurationsController;

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

    // Configurations (CISA Products)
    Route::get('/configurations', [ConfigurationsController::class, 'index'])->name('configurations.index');
    Route::post('/configurations/products', [ConfigurationsController::class, 'storeProduct'])->name('configurations.products.store');
    Route::put('/configurations/products/{product}', [ConfigurationsController::class, 'updateProduct'])->name('configurations.products.update');
    Route::post('/configurations/products/{product}/gl-codes', [ConfigurationsController::class, 'addGlCode'])->name('configurations.products.glCodes.store');
    Route::delete('/configurations/products/{product}/gl-codes/{glCode}', [ConfigurationsController::class, 'deleteGlCode'])->name('configurations.products.glCodes.delete');
    Route::delete('/configurations/products/{product}', [ConfigurationsController::class, 'destroy'])->name('configurations.products.delete');

    // Title Configuration routes
    Route::get('/configurations/title', [ConfigurationsController::class, 'title'])->name('configurations.title');
    Route::post('/configurations/title', [ConfigurationsController::class, 'storeTitle'])->name('configurations.title.store');
    Route::put('/configurations/title/{titleConfiguration}', [ConfigurationsController::class, 'updateTitle'])->name('configurations.title.update');
    Route::delete('/configurations/title/{titleConfiguration}', [ConfigurationsController::class, 'destroyTitle'])->name('configurations.title.delete');
    Route::post('/configurations/title/{titleConfiguration}/mbwin', [ConfigurationsController::class, 'addTitleMbwinCode'])->name('configurations.title.mbwin.store');
    Route::delete('/configurations/title/{titleConfiguration}/mbwin/{mbwinCode}', [ConfigurationsController::class, 'deleteTitleMbwinCode'])->name('configurations.title.mbwin.delete');

    // Gender Configuration routes
    Route::get('/configurations/gender', [ConfigurationsController::class, 'gender'])->name('configurations.gender');
    Route::post('/configurations/gender', [ConfigurationsController::class, 'storeGender'])->name('configurations.gender.store');
    Route::put('/configurations/gender/{genderConfiguration}', [ConfigurationsController::class, 'updateGender'])->name('configurations.gender.update');
    Route::delete('/configurations/gender/{genderConfiguration}', [ConfigurationsController::class, 'destroyGender'])->name('configurations.gender.delete');
    Route::post('/configurations/gender/{genderConfiguration}/mbwin', [ConfigurationsController::class, 'addGenderMbwinCode'])->name('configurations.gender.mbwin.store');
    Route::delete('/configurations/gender/{genderConfiguration}/mbwin/{mbwinCode}', [ConfigurationsController::class, 'deleteGenderMbwinCode'])->name('configurations.gender.mbwin.delete');

    // Civil Status Configuration routes
    Route::get('/configurations/civil', [ConfigurationsController::class, 'civil'])->name('configurations.civil');
    Route::post('/configurations/civil', [ConfigurationsController::class, 'storeCivil'])->name('configurations.civil.store');
    Route::put('/configurations/civil/{civilConfiguration}', [ConfigurationsController::class, 'updateCivil'])->name('configurations.civil.update');
    Route::delete('/configurations/civil/{civilConfiguration}', [ConfigurationsController::class, 'destroyCivil'])->name('configurations.civil.delete');
    Route::post('/configurations/civil/{civilConfiguration}/mbwin', [ConfigurationsController::class, 'addCivilMbwinCode'])->name('configurations.civil.mbwin.store');
    Route::delete('/configurations/civil/{civilConfiguration}/mbwin/{mbwinCode}', [ConfigurationsController::class, 'deleteCivilMbwinCode'])->name('configurations.civil.mbwin.delete');

    // Branch routes
    Route::get('/branches', [App\Http\Controllers\BranchController::class, 'index'])->name('branches.index');
    Route::post('/branches', [App\Http\Controllers\BranchController::class, 'store'])->name('branches.store');
    Route::put('/branches/{branch}', [App\Http\Controllers\BranchController::class, 'update'])->name('branches.update');
    Route::delete('/branches/{branch}', [App\Http\Controllers\BranchController::class, 'destroy'])->name('branches.destroy');
});
