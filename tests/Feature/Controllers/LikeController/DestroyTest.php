<?php

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use Illuminate\Database\Eloquent\Model;

it('requires authentication', function () {
    delete(route('likes.destroy', [
        'type' => 'post',
        'id' => 1,
    ]))->assertRedirect(route('login'));
});

it('allows unliking a likeable', function (Model $likeable) {
    /** @var User $user */
    $user = User::factory()->create();

    $like = Like::factory()->for($user)->for($likeable, 'likeable')->create();

    actingAs($user)
        ->fromRoute('dashboard')
        ->delete(route('likes.destroy', [
            'type' => $likeable->getMorphClass(),
            'id' => $likeable->id,
        ]))
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseEmpty(Like::class);
    expect($likeable->refresh()->likes_count)->toBe(0);
})->with([
    fn() => Post::factory()->create(['likes_count' => 1]),
    fn() => Comment::factory()->create(['likes_count' => 1]),
]);

it('prevents unliking something you havent liked', function () {
    $likeable = Post::factory()->create();

    /** @var User $user */
    $user = User::factory()->create();
    actingAs($user)
        ->delete(route('likes.destroy', [
            'type' => $likeable->getMorphClass(),
            'id' => $likeable->id,
        ]))
        ->assertForbidden();
});

it('only allows unliking supported models', function () {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->delete(route('likes.destroy', [
            'type' => $user->getMorphClass(),
            'id' => $user->id,
        ]))
        ->assertForbidden();
});

it('throws a 404 if the type is unsupported', function () {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->delete(route('likes.destroy', [
            'type' => 'unsupported',
            'id' => 1,
        ]))
        ->assertNotFound();
});
