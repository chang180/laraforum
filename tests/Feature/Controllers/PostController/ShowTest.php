<?php

use App\Http\Resources\CommentResource;
use App\Models\Post;
use function Pest\Laravel\get;
use Inertia\Testing\AssertableInertia as Assert;
use App\Http\Resources\PostResource;
use App\Models\Comment;

it('can show a post', function () {
    $post = Post::factory()->create();

    get($post->showRoute())
        ->assertInertia(fn (Assert $page) => $page
            ->component('Post/Show')
        );
});

it('passes a post to the view', function () {
    $post = Post::factory()->create();
    $post->load('user', 'topic');

    get($post->showRoute())
        ->assertInertia(fn (Assert $page) => $page
            ->component('Post/Show')
            ->has('post', fn (Assert $page) => $page
                ->where('id', $post->id)
                ->where('title', $post->title)
                ->where('user.id', $post->user->id)
                ->where('topic.id', $post->topic->id)
            )
        );
});

it('passes comments to the view', function () {
    $post = Post::factory()->create();
    $comments = Comment::factory(2)->for($post)->create();
    $comments->load('user');

    get($post->showRoute())
        ->assertInertia(fn (Assert $page) => $page
            ->component('Post/Show')
            ->has('comments.data', 2, fn (Assert $page) => $page
                ->where('0.id', $comments->reverse()->values()->get(0)->id)
                ->where('1.id', $comments->reverse()->values()->get(1)->id)
            )
        );
});

it('will redirect if the slug is incorrect', function () {
    $post = Post::factory()->create(['title' => 'Hello world']);

    get(route('posts.show', [$post, 'incorrect-slug']))
        ->assertRedirect($post->showRoute());
});
