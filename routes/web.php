<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authenticated user routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - different for admin and employee
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('feedback.submit');
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Feedback submission (all authenticated users)
    Route::get('/feedback/submit', [FeedbackController::class, 'submit'])->name('feedback.submit');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
});

// Admin-only routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Category Management
    Route::resource('categories', CategoryController::class);

    // Feedback Management
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');

    // Moderation
    Route::get('/moderation', [ModerationController::class, 'index'])->name('moderation.index');
    Route::post('/moderation/{feedback}/approve', [ModerationController::class, 'approve'])->name('moderation.approve');
    Route::post('/moderation/{feedback}/flag', [ModerationController::class, 'flag'])->name('moderation.flag');
    Route::post('/moderation/{feedback}/reset', [ModerationController::class, 'reset'])->name('moderation.reset');

    // Reports & Analytics
    Route::get('/reports/analytics', [ReportController::class, 'analytics'])->name('reports.analytics');
    Route::get('/reports/trends', [ReportController::class, 'trends'])->name('reports.trends');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
});

require __DIR__.'/auth.php';
