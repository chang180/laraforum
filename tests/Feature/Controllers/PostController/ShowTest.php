<?php

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Topic;
use function Pest\Laravel\get;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia;


it('will redirect if the slug is incorrect', function () {
    $post = Post::factory()->create(['title' => 'Hello world']);

    get(route('posts.show', [$post, 'incorrect-slug']))
        ->assertRedirect($post->showRoute());
});
