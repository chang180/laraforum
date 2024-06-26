<?php

use App\Http\Resources\CommentResource;
use App\Models\Post;

use function Pest\Laravel\get;
use App\Http\Resources\PostResource;
use App\Models\Comment;

it('can show a post' , function () {
    $post = Post::factory()->create();

    get($post->showRoute())
        ->assertComponent('Post/Show', true);
});

it('passes a post to the view', function () {
    $post = Post::factory()->create();

    $post->load('user', 'topic');

    get($post->showRoute())
        ->assertHasResource('post', PostResource::make($post));
});

it('passes comments to the view', function () {
    $post = Post::factory()->create();

    $comments = Comment::factory(2)->for($post)->create();
    $comments->load('user');

    get($post->showRoute())
        ->assertHasPaginatedResource('comments', CommentResource::collection($comments->reverse()));
});

it('will redirect if the slug is incorrect', function () {
    $post = Post::factory()->create(['title' => 'Hello world']);

    get(route('posts.show', [$post, 'incorrect-slug']))
        ->assertRedirect($post->showRoute());
});
