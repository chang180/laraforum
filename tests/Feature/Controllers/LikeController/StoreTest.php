<?php

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;
use Illuminate\Database\Eloquent\Model;

it('requires authentication', function () {
    post(route('likes.store',[
        'type' => 'post',
        'id' => 1,
    ]))->assertRedirect(route('login'));
});

it('allows liking a likeable', function (Model $likeable) {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->fromRoute('dashboard')
        ->post(route('likes.store', [
            'type' => $likeable->getMorphClass(),
            'id' => $likeable->id,
        ]))
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas(Like::class, [
        'user_id' => $user->id,
        'likeable_id' => $likeable->id,
        'likeable_type' => $likeable->getMorphClass(),
    ]);
    expect($likeable->refresh()->likes_count)->toBe(1);
})->with([
    fn() => Post::factory()->create(),
    fn() => Comment::factory()->create(),
]);

it('prevents liking something you already liked', function () {
    $like = Like::factory()->create();

    $likeable = $like->likeable;

    actingAs($like->user)
        ->post(route('likes.store', [
            'type' => $likeable->getMorphClass(),
            'id' => $likeable->id,
        ]))
        ->assertForbidden();
});


it('only allows liking supported models', function () {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('likes.store', [
            'type' => $user->getMorphClass(),
            'id' => $user->id,
        ]))
        ->assertForbidden();
});

it('throws a 404 if the type is unsupported', function () {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('likes.store', [
            'type' => 'unsupported',
            'id' => 1,
        ]))
        ->assertNotFound();
});
