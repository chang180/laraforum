<?php

use App\Models\User;
use App\Models\Topic;
use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;
use App\Http\Resources\TopicResource;
use Inertia\Testing\AssertableInertia;

it('requires authentication', function () {
    get(route('posts.create'))
        ->assertRedirect(route('login'));
});

it('returns the correct component', function () {
    actingAs(User::factory()->create())
        ->get(route('posts.create'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Post/Create')
        );
});

it('passes topics to the view', function () {
    $topics = Topic::factory(2)->create();

    actingAs(User::factory()->create())
        ->get(route('posts.create'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Post/Create')
            ->has('topics', TopicResource::collection($topics)->count())
        );
});


