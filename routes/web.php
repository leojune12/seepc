<?php

use App\Http\Controllers\PublicationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/publication', [PublicationController::class, 'index'])->name('publication');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/publication/create', [PublicationController::class, 'create'])->name('publication.create');

    Route::post('/publication/store', [PublicationController::class, 'store'])->name('publication.store');
});
