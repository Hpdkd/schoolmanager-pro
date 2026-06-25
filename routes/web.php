<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GradeController;
use Illuminate\Support\Facades\Route;

// Railway healthcheck - no auth, no DB, no redirect
Route::get('/health', fn() => response('OK', 200));

// Page d'accueil → rediriger vers dashboard
Route::get('/', fn() => redirect()->route('dashboard'));

// ─── Routes authentifiées ────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Élèves ───────────────────────────────────────────────────────────────
    Route::resource('students', StudentController::class);

    // Bulletin PDF
    Route::get('students/{student}/report-card/{semester}', [StudentController::class, 'reportCard'])
        ->name('students.report-card')
        ->where('semester', 'S1|S2');

    // ── Notes ────────────────────────────────────────────────────────────────
    Route::get('/grades',         [GradeController::class, 'index'])->name('grades.index');
    Route::post('/grades/bulk',   [GradeController::class, 'storeBulk'])->name('grades.store-bulk');
    Route::get('/grades/results', [GradeController::class, 'classResults'])->name('grades.results');
    Route::get('/grades/export',  [GradeController::class, 'export'])->name('grades.export');

});

// Routes d'authentification (générées par Laravel Breeze)
require __DIR__ . '/auth.php';
