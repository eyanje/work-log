<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BookICalendarController;
use App\Http\Controllers\RecordController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('library', [BookController::class, 'index']
)->middleware(['auth', 'verified'])->name('library');

Route::get('books/new', function () {
    return Inertia::render('NewBook');
})->middleware(['auth', 'verified'])->name('books.new');

Route::put('books', [BookController::class, 'create']
)->middleware(['auth', 'verified'])->name('books.create');

Route::get('book/{id}', [BookController::class, 'show']
)->middleware(['auth', 'verified'])->name('book.show');

Route::get('book/{id}/edit', [BookController::class, 'edit']
)->middleware(['auth', 'verified'])->name('book.edit');

Route::patch('book/{id}', [BookController::class, 'update']
)->middleware(['auth', 'verified'])->name('book.update');

// Editing records

Route::post('book/{bookId}', [RecordController::class, 'create']
)->middleware(['auth', 'verified'])->name('record.create');

Route::delete('book/{bookId}/record/{recordId}', [RecordController::class, 'delete']
)->middleware(['auth', 'verified'])->name('record.delete');


// Deleting books

Route::get('book-deleted', function () {
    return Inertia::render('BookDeleted');
})->middleware(['auth', 'verified'])->name('book.deleted');

Route::delete('book/{id}', [BookController::class, 'delete']
)->middleware(['auth', 'verified'])->name('book.delete');

// Bookmarks

Route::post('book/{id}/bookmark', [BookController::class, 'bookmark']
)->middleware(['auth', 'verified'])->name('book.bookmark');

Route::delete('book/{id}/bookmark', [BookController::class, 'unbookmark']
)->middleware(['auth', 'verified'])->name('book.unbookmark');

// Exporting

Route::get('book/{id}/export', [BookICalendarController::class, 'export']
)->middleware(['auth', 'verified'])->name('book.export');

Route::post('book/{id}/import', [BookICalendarController::class, 'import']
)->middleware(['auth', 'verified'])->name('book.import');

require __DIR__.'/settings.php';

require __DIR__.'/auth.php';
