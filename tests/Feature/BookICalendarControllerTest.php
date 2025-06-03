<?php

use App\Models\Book;
use App\Models\Record;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Sabre\VObject\Component\VCalendar;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('export book', function () {
    $user = User::factory()->has(
        Book::factory()->has(
            Record::factory()->state([
                'content' => 'summary',
                'created_at' => '20250521T175517Z',
                'updated_at' => '20250521T175517Z',
            ])
        )
    )->create();

    $book = $user->books()->get()[0];

    $response = $this
        ->actingAs($user)
        ->get("/book/{$book->id}/export");

    $response
        ->assertSessionHasNoErrors()
        ->assertStreamed()
        ->assertDownload();
});

test('import book', function () {
    $user = User::factory()->has(
        Book::factory()
    )->create();

    $book = $user->books()->get()[0];

    $calendar = new VCalendar;
    $calendar->add('VJOURNAL', [
        'UID' => "work-log-1",
        'DTSTAMP' => '20250521T175518Z',
        'SUMMARY' => 'imported summary',
        'DTSTART' => '20250521T175517Z',
    ]);

    $file = UploadedFile::fake()
        ->createWithContent('journal-import.ics', $calendar->serialize());

    $response = $this
        ->actingAs($user)
        ->post("/book/{$book->id}/import", [
            'book' => $file,
        ]);

    $response->assertRedirect(route('book.edit', ['id' => $book->id]));

    // Reload book data
    $book = $user->books()->get()[0];
    $this->assertCount(1, $book->records);

    $record = $book->records()->get()[0];
    $this->assertEquals('imported summary', $record->content);
    $this->assertEquals(
        DateTimeImmutable::createFromFormat('Ymd\\THise', '20250521T175517Z'),
        $record->started_at);
});

test('import invalid data', function () {
    $user = User::factory()->has(
        Book::factory()
    )->create();

    $book = $user->books()->get()[0];

    $file = UploadedFile::fake()
        ->createWithContent('journal-import.ics', 'malformed data');

    $response = $this
        ->actingAs($user)
        ->post("/book/{$book->id}/import", [
            'book' => $file,
        ]);

    $response->assertServerError();

    // Reload book data
    $book = $user->books()->get()[0];
    $this->assertCount(0, $book->records);
});
