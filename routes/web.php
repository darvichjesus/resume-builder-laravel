<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResumeController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Redirect dashboard to our resume builder dashboard 
    Route::get('/dashboard', function () {
        return redirect()->route('resume.index');
    })->name('dashboard');

    Route::get('/resume', [ResumeController::class, 'index'])->name('resume.index');
    Route::post('/resume/save', [ResumeController::class, 'save'])->name('resume.save');
    Route::get('/resume/preview', [ResumeController::class, 'preview'])->name('resume.preview');
    Route::get('/resume/download', [ResumeController::class, 'download'])->name('resume.download');
    Route::get('/resume/cover', [ResumeController::class, 'downloadCover'])->name('resume.cover');
    
    // AI Assistant Route
    Route::post('/resume/ai-suggest', [\App\Http\Controllers\AIAssistantController::class, 'suggest'])->name('ai.suggest');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public Portfolio Resume Digital View
Route::get('/p/{slug}', [App\Http\Controllers\PortfolioController::class, 'show'])->name('portfolio.show');

require __DIR__.'/auth.php';
