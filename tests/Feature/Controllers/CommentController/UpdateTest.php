<?php

use App\Models\Comment;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\put;


it('requres authentication', function () {
    put(route('comments.update', Comment::factory()->create()))
        ->assertRedirect(route('login'));
});

it('can update a comment', function () {
    $comment = Comment::factory()->create(['body' => 'Old body']);

    $newBody = 'New body';

    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body' => $newBody]);

    $this->assertDatabaseHas('comments', ['id' => $comment->id, 'body' => $newBody]);
});

it('redirects to the post show page', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body' => 'New body'])
        ->assertRedirect($comment->post->showRoute());
});

it('redirects to the correct page of comments', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', ['comment' => $comment, 'page' => 2]), ['body' => 'New body'])
        ->assertRedirect($comment->post->showRoute(['page' => 2]));
});

it('cannot update a comment from another user', function () {
    $comment = Comment::factory()->create();

    actingAs(Comment::factory()->create()->user)
        ->put(route('comments.update', $comment), ['body' => 'New body'])
        ->assertForbidden();
});

it('requires a valid body', function ($value) {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body' => $value])
        ->assertSessionHasErrors('body');
})
    ->with([
        '',
        null,
        true,
        1,
        1.5,
        str_repeat('a', 2501),
    ]);
