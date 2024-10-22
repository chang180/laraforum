<?php

use function Pest\Laravel\get;

it('requires authentication', function () {
    get(route('posts.create'))
        ->assertRedirect(route('login'));
});
