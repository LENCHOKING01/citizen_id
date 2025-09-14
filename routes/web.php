<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\CitizenRegistrationController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\BiometricController;
use App\Http\Controllers\PrintJobController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public routes for citizen verification
Route::get('/verify/{idNumber}', [CitizenRegistrationController::class, 'verify'])->name('citizen.verify');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Citizens Management
    Route::resource('citizens', CitizenController::class);
    Route::get('/citizens/{citizen}/id-card', [CitizenController::class, 'generateIdCard'])->name('citizens.id-card');
    Route::get('/citizens/{citizen}/id-card/view', [CitizenController::class, 'viewIdCard'])->name('citizens.id-card.view');
    Route::post('/citizens/{citizen}/photo', [CitizenController::class, 'uploadPhoto'])->name('citizens.upload-photo');
    
    // Applications Management
    Route::resource('applications', ApplicationController::class);
    Route::post('applications/{application}/documents', [ApplicationController::class, 'uploadDocument'])->name('applications.upload-document');
    Route::get('applications/{application}/biometrics', [ApplicationController::class, 'captureBiometric'])->name('applications.capture-biometric');
    Route::post('applications/{application}/biometrics', [ApplicationController::class, 'storeBiometric'])->name('applications.store-biometric');
    
    // Print Jobs
    Route::resource('print-jobs', PrintJobController::class)->only(['index', 'show', 'update']);
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Quick test routes
Route::get('/test/citizen-id', function () {
    $citizen = new \App\Models\Citizen();
    return response()->json([
        'generated_id' => $citizen->generateCitizenId(),
        'format' => 'YYYY######'
    ]);
})->name('test.citizen-id');

require __DIR__.'/auth.php';
