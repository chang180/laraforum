<?php

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Topic;
use function Pest\Laravel\get;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia;


it('can show a post', function () {
    $post = Post::factory()->create();

    $response = get(route('posts.show', ['post' => $post->id, 'slug' => Str::slug($post->title)]));

    // 檢查響應是有效的 Inertia 響應
    $response->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Post/Show')
        ->where('post.id', $post->id)
        ->where('post.title', $post->title)
        ->has('comments')
    );
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
