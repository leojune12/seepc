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

Route::get('/publications', [PublicationController::class, 'index'])->name('publications');

Route::get('/publications/show/{id}', [PublicationController::class, 'show'])->name('publications.show');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/publications/create', [PublicationController::class, 'create'])->name('publications.create');

    Route::post('/publications/store', [PublicationController::class, 'store'])->name('publications.store');
});
