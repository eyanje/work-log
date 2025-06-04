<?php

use App\Models\Book;
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

test('update book metadata', function () {
    $user = User::factory()->has(
        Book::factory()
    )->create();

    $book = $user->books()->get()[0];

    $response = $this
        ->actingAs($user)
        ->patch("/book/{$book->id}", ['title' => 'test title']);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect("/book/{$book->id}/edit");

    // Reread book data
    $book = $user->books()->get()[0];
    $this->assertSame($book->title, 'test title');
});

test('delete book', function () {
    $user = User::factory()->has(
        Book::factory()
    )->create();

    $book = $user->books()->get()[0];

    $response = $this
        ->actingAs($user)
        ->delete("/book/{$book->id}");

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/book-deleted');

    $this->assertModelMissing($book);
});
