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
        ->assertRedirect($comment->post->showRoute());
});

it('prevent deleting a comment from another user', function () {
    $comment = Comment::factory()->create();

    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user)
        ->delete(route('comments.destroy', $comment))
        ->assertForbidden();

});

it('prevents deleting a comment posted over an hour ago', function () {

    $this->freezeTime();
    $comment = Comment::factory()->create();

    $this->travel(1)->hours();

    actingAs($comment->user)
        ->delete(route('comments.destroy', $comment))
        ->assertForbidden();
});

it('redirects to the post show page with the page query parameter', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->delete(route('comments.destroy', ['comment' => $comment, 'page' => 2]))
        ->assertRedirect($comment->post->showRoute(['page' => 2]));
});

