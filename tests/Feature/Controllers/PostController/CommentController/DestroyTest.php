<?php

use App\Models\Post;
use App\Models\Comment;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;


it('requires authentication', function () {
    delete(route('comments.destroy', Post::factory()->create(), Comment::factory()->create()))
        ->assertRedirect(route('login'));
});

it('can delete a comment', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)->delete(route('comments.destroy', $comment));

    assertModelMissing($comment);
});

it('redirects to the post show page', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->delete(route('comments.destroy', $comment))
        ->assertRedirect(route('posts.show', $comment->post_id));
});

it('prevent deleting a comment from another user', function () {
    $comment = Comment::factory()->create();

    actingAs(User::factory()->create())
        ->delete(route('comments.destroy', $comment))
        ->assertForbidden();

});
