<?php

use App\Models\Book;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

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
