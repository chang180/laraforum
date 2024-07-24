<?php

use App\Models\Post;
use function Pest\Laravel\get;

it('will redirect if the slug is incorrect', function (string $incorrectSlug) {
    $post = Post::factory()->create(['title' => 'Hello world']);

    get(route('posts.show', [$post, $incorrectSlug, 'page' => 2]))
        ->assertRedirect($post->showRoute(['page' => 2]));
})->with([
    'foo-bar',
    'hello',
]);
