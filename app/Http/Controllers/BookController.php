<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{
    public function create(Request $request) {
        $name = $request->input('name');
        $book = $request->user()->books()->create([
            'name' => $name,
        ]);

        return redirect()->route('book.show', ['id' => $book->id]);
    }

    public function show(Request $request, string $id)
    {
        $book = $request->user()->books()->findOrFail($id);

        return Inertia::render('Book', [
            'book' => $book,
            'records' => $book->records()->orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function append(Request $request, string $id)
    {
        $content = $request->input('content');

        $book = $request->user()->books->findOrFail($id);
        $book->records()->create([
            'content' => $content,
        ]);

        return redirect()->route('book.show', ['id' => $id]);
    }

    public function edit(Request $request, string $id) {
        $book = $request->user()->books()->findOrFail($id);

        return Inertia::render('EditBook', ['book' => $book]);
    }

    public function update(Request $request, string $id) {
        $book = $request->user()->books()->findOrFail($id);
        $name = $request->input('name');

        $book->name = $name;
        $book->save();

        return redirect()->route('book.edit', ['id' => $book->id]);
    }

    public function delete(Request $request, string $id) {
        $book = $request->user()->books()->findOrFail($id);
        $book->delete();

        return redirect()->route('book.deleted');
    }
}
