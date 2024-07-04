<?php

use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\get;

it('should return the correct component', function () {
    get(route('posts.index'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Post/Index')
            ->has('jetstream') // 確認 jetstream 屬性存在
            ->has('auth') // 確認 auth 屬性存在
            ->has('posts') // 確認 posts 屬性存在
            ->has('posts.data') // 確認 posts.data 屬性存在
            ->has('posts.links') // 確認 posts.links 屬性存在
            ->has('posts.meta') // 確認 posts.meta 屬性存在
        );
});

it('passes posts to the view', function () {
    // 創建測試數據
    $posts = Post::factory(3)->create();

    // 測試路由並驗證 Inertia 響應
    get(route('posts.index'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Post/Index')
            ->has('posts.data', 3) // 確認有3條數據
            ->has('posts.links') // 確認有分頁鏈接
            ->has('posts.meta') // 確認有分頁元數據
            ->where('posts.data', fn ($data) =>
                $data->contains('id', $posts->first()->id) &&
                $data->contains('id', $posts->get(1)->id) &&
                $data->contains('id', $posts->get(2)->id)
            ) // 確認數據包含所有測試ID
        );
});

it('passes topics to the view', function () {
    $topics = Topic::factory(3)->create();

    get(route('posts.index'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('topics', 3) // 確認 topics 屬性存在且有3個元素
        );
});


it('can filter to a topic', function () {
    $general = Topic::factory()->create();
    $posts = Post::factory(2)->for($general)->create();
    $otherPosts = Post::factory(3)->create();

    $posts->load(['user', 'topic']);

    get(route('posts.index', ['topic' => $general]))
        ->assertHasPaginatedResource('posts', PostResource::collection($posts->reverse()));
});

it('passes the selected topic to the view', function () {
    $topic = Topic::factory()->create();

    get(route('posts.index', ['topic' => $topic]))
        ->assertHasResource('selectedTopic', TopicResource::make($topic));
});
