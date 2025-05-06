<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{
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
}
