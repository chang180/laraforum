<?php

use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use Inertia\Testing\AssertableInertia as Assert;
use function Pest\Laravel\get;

it('should return the correct component', function () {
    get(route('posts.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Post/Index')
        );
});

it('passes posts to the view', function () {
    $posts = Post::factory(3)->create();

    $posts->load(['user', 'topic']);

    get(route('posts.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Post/Index')
            ->has('posts.data', 3, fn (Assert $page) => $page
                ->where('0.id', $posts->reverse()->values()->get(0)->id)
                ->where('1.id', $posts->reverse()->values()->get(1)->id)
                ->where('2.id', $posts->reverse()->values()->get(2)->id)
            )
        );
});

it('passes topics to the view', function () {
    $topics = Topic::factory(3)->create();

    get(route('posts.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Post/Index')
            ->has('topics', 3, fn (Assert $page) => $page
                ->where('0.id', $topics[0]->id)
                ->where('1.id', $topics[1]->id)
                ->where('2.id', $topics[2]->id)
            )
        );
});

it('can filter to a topic', function () {
    $general = Topic::factory()->create();
    $posts = Post::factory(2)->for($general)->create();
    $otherPosts = Post::factory(3)->create();

    $posts->load(['user', 'topic']);

    get(route('posts.index', ['topic' => $general]))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Post/Index')
            ->has('posts.data', 2, fn (Assert $page) => $page
                ->where('0.id', $posts->reverse()->values()->get(0)->id)
                ->where('1.id', $posts->reverse()->values()->get(1)->id)
            )
        );
});

it('passes the selected topic to the view', function () {
    $topic = Topic::factory()->create();

    get(route('posts.index', ['topic' => $topic]))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Post/Index')
            ->has('selectedTopic', fn (Assert $page) => $page
                ->where('id', $topic->id)
            )
        );
});
