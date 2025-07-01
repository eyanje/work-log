<?php

use App\Models\Book;
use App\Models\Record;
use App\Models\User;
use Illuminate\Support\DateFactory;
use Illuminate\Support\Str;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('append to book', function () {

    $user = User::factory()->has(
        Book::factory()
    )->create();

    $book = $user->books()->get()[0];

    $dateFactory = new DateFactory;
    $startedAt = $dateFactory->createFromTimestampUTC(1e10);

    $content = Str::random(10);
    $response = $this
        ->actingAs($user)
        ->from("/book/{$book->id}")
        ->post("/book/{$book->id}", [
            'content' => $content,
            'started_at' => $startedAt,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect("/book/{$book->id}");

    $this->assertCount(1, $book->records);
    $this->assertEquals($content, $book->records[0]->content);
    $this->assertEquals($startedAt, $book->records[0]->started_at);
    $this->assertNull($book->records[0]->ended_at);
});

test('append to book with end time', function () {

    $user = User::factory()->has(
        Book::factory()
    )->create();

    $book = $user->books()->get()[0];

    $dateFactory = new DateFactory;
    $startedAt = $dateFactory->createFromTimestampUTC(1e10);
    $endedAt = $dateFactory->createFromTimestampUTC(1e10 + 1e6);

    $content = Str::random(10);
    $response = $this
        ->actingAs($user)
        ->from("/book/{$book->id}")
        ->post("/book/{$book->id}", [
            'content' => $content,
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect("/book/{$book->id}");

    $this->assertCount(1, $book->records);
    $this->assertNotNull($book->records[0]->uid);
    $this->assertEquals($content, $book->records[0]->content);
    $this->assertEquals($startedAt, $book->records[0]->started_at);
    $this->assertEquals($endedAt, $book->records[0]->ended_at);
});

test('cannot end before start', function () {

    $user = User::factory()->has(
        Book::factory()
    )->create();

    $book = $user->books()->get()[0];

    $dateFactory = new DateFactory;
    $startedAt = $dateFactory->createFromTimestampUTC(1e10);
    $endedAt = $dateFactory->createFromTimestampUTC(1e10 - 1e6);

    $content = Str::random(10);
    $response = $this
        ->actingAs($user)
        ->from("/book/{$book->id}")
        ->post("/book/{$book->id}", [
            'content' => $content,
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
        ]);

    $response->assertInvalid('ended_at');

    $this->assertEmpty($book->records);
});


test('delete record', function () {
    $user = User::factory()->has(
        Book::factory()->has(
            Record::factory()->count(10)
        )
    )->create();

    $book = $user->books()->get()[0];
    $record = $book->records[0];

    $response = $this
        ->actingAs($user)
        ->from("/book/{$book->id}")
        ->delete("/book/{$book->id}/record/{$record->id}");

    $response->assertRedirect("/book/{$book->id}");

    $this->assertModelMissing($record);
});








