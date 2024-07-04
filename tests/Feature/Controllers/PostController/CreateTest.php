<?php
use App\Models\User;
use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;
use App\Testing\CustomAssertableInertia;
use App\Models\Topic;

it('requires authentication', function () {
    get(route('posts.create'))
        ->assertRedirect(route('login'));
});


