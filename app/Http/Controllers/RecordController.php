<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RecordController extends Controller {
    public function create(Request $request, string $bookId)
    {
        $request->validate([
            'content' => ['required'],
            'started_at' => ['required', 'date'],
            'ended_at' => ['date', 'after_or_equal:started_at'],
        ]);

        $book = $request->user()->books->findOrFail($bookId);
        $book->records()->create([
            'uid' => Str::uuid7(),
            'content' => $request->input('content'),
            'started_at' => $request->input('started_at'),
            'ended_at' => $request->input('ended_at'),
        ]);

        return redirect()->route('book.show', ['id' => $bookId]);
    }

    public function delete(Request $request, string $bookId, string $recordId) {
        $book = $request->user()->books->findOrFail($bookId);
        $record = $book->records->findOrFail($recordId);

        $record->delete();

        return redirect()->route('book.show', ['id' => $bookId]);
    }

}
