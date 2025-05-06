<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('book/{id}', [BookController::class, 'show']
)->middleware(['auth', 'verified'])->name('book.show');

Route::post('book/{id}', [BookController::class, 'append']
)->middleware(['auth', 'verified'])->name('book.append');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
