<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{
    public function index(Request $request) {
        $books = $request->user()->books;

        return Inertia::render('Library', ['books' => $books]);
    }

    public function create(Request $request) {
        $title = $request->input('title');
        $book = $request->user()->books()->create([
            'title' => $title,
        ]);

        return redirect()->route('book.show', ['id' => $book->id]);
    }

    public function show(Request $request, string $id)
    {
        $book = $request->user()->books()->findOrFail($id);

        return Inertia::render('Book', [
            'book' => $book,
            'records' => $book->records()->orderBy('started_at', 'desc')->get(),
        ]);
    }

    public function append(Request $request, string $id)
    {
        $request->validate([
            'content' => ['required'],
            'started_at' => ['required', 'date'],
            'ended_at' => ['date', 'after_or_equal:started_at'],
        ]);

        $book = $request->user()->books->findOrFail($id);
        $book->records()->create([
            'content' => $request->input('content'),
            'started_at' => $request->input('started_at'),
            'ended_at' => $request->input('ended_at'),
        ]);

        return redirect()->route('book.show', ['id' => $id]);
    }

    public function edit(Request $request, string $id) {
        $book = $request->user()->books()->findOrFail($id);

        return Inertia::render('EditBook', ['book' => $book]);
    }

    public function update(Request $request, string $id) {
        $book = $request->user()->books()->findOrFail($id);
        $title = $request->input('title');

        $book->title = $title;
        $book->save();

        return redirect()->route('book.edit', ['id' => $book->id]);
    }

    public function delete(Request $request, string $id) {
        $book = $request->user()->books()->findOrFail($id);
        $book->delete();

        return redirect()->route('book.deleted');
    }

    public function bookmark(Request $request, string $id) {
        $book = $request->user()->books()->findOrFail($id);

        $book->bookmarked = true;
        $book->save();

        return redirect()->route('book.show', ['id' => $book->id]);
    }

    public function unbookmark(Request $request, string $id) {
        $book = $request->user()->books()->findOrFail($id);

        $book->bookmarked = false;
        $book->save();

        return redirect()->route('book.show', ['id' => $book->id]);
    }
}
