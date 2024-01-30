<?php

use App\Models\Post;
use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\get;

it('should return the coorent component', function () {
    get(route('posts.index'))
        ->assertInertia(
            fn (AssertableInertia $inertia) => $inertia
                ->component('Post/Index', true)
        );
});

it('passes posts to the view', function () {
    get(route('posts.index'))
        ->assertInertia(
            fn (AssertableInertia $inertia) => $inertia
                ->has('posts')
                ->where('posts', Post::all()->toArray())
        );
});
