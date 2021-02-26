<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PublicationController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    /*return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ])*/

    return Redirect::route('publications');
});

Route::get('/publications', [PublicationController::class, 'index'])->name('publications');

Route::get('/publications/show/{id}', [PublicationController::class, 'show'])->name('publications.show');

Route::post('/publications/get-publications', [PublicationController::class, 'get_publications'])->name('publications.get-publications');

Route::post('/publications/comment/show', [CommentController::class, 'show_comments'])->name('publications.comment.show');

Route::post('/publications/comment/reply/show', [CommentController::class, 'show_replies'])->name('publications.comment.reply.show');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/publications/create', [PublicationController::class, 'create'])->name('publications.create');

    Route::post('/publications/store', [PublicationController::class, 'store'])->name('publications.store');


    Route::post('/publications/like', [PublicationController::class, 'like'])->name('publications.like');

    Route::post('/publications/unlike', [PublicationController::class, 'unlike'])->name('publications.unlike');

    Route::post('/publications/comment/store', [CommentController::class, 'store'])->name('publications.comment.store');

    Route::post('/publications/comment/reply/store', [CommentController::class, 'store_reply'])->name('publications.comment.reply.store');
});
