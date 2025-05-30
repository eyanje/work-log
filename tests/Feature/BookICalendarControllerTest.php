<?php

use App\Models\Book;
use App\Models\Record;
use App\Models\User;

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
