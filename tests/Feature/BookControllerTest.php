<?php

use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Str;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('append to book', function () {

    $user = User::factory()->has(
        Book::factory()
    )->create();

    $book = $user->books()->get()[0];

    $content = Str::random(10);
    $response = $this
        ->actingAs($user)
        ->from("/book/{$book->id}")
        ->post("/book/{$book->id}", ['content' => $content]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect("/book/{$book->id}");
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
