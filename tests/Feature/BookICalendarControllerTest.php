<?php

use App\Models\Book;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('append to book', function () {
    $user = User::factory()->has(
        Book::factory()
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

