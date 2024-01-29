<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)
            ->create();

        $posts = Post::factory(200)->recycle($users)
            ->create();

        $comments = Comment::factory(100)->recycle($users, $posts)->create();

        $chang180 = User::factory()
        ->has(Post::factory()->count(10))
        ->has(Comment::factory()->count(50)->recycle($posts))
        ->create([
            'name' => 'chang180',
            'email' => 'chang180@gmail.com',
        ]);
    }
}
