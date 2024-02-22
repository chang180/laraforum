<?php

use \App\Models\Post;


it('uses title case for titles', function () {
    $post = Post::factory()->create([
        'title' => 'Hi, its my first post',
    ]);

    expect($post->title)->toBe('Hi, Its My First Post');
});
