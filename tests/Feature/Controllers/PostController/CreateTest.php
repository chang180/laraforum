<?php

use App\Http\Resources\TopicResource;
use App\Models\Topic;
use App\Models\User;
use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;
use Inertia\Testing\AssertableInertia as Assert;

it('requires authentication', function () {
    get(route('posts.create'))
        ->assertRedirect(route('login'));
});

it('returns the correct component',function(){
    actingAs(User::factory()->create())
        ->get(route('posts.create'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Post/Create')
        );
});

it('passes topics to the view', function () {
    $topics = Topic::factory(2)->create();

    actingAs(User::factory()->create())
        ->get(route('posts.create'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Post/Create')
            ->has('topics', fn (Assert $page) => $page
                ->where('0.id', $topics[0]->id)
                ->where('1.id', $topics[1]->id)
            )
        );
});


