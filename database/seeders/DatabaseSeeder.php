<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TopicSeeder::class,
        ]);

        $topics = Topic::all();

        $users = User::factory(10)
            ->create();

        $posts = Post::factory(200)
            ->withFixture()
            ->has(Comment::factory(12)->recycle($users))
            ->recycle([$users, $topics])
            ->create();


        if (!User::where('name', 'chang180')->exists()) {
            $chang180 = User::factory()
            ->has(Post::factory()->count(10)->recycle($topics)->withFixture())
            ->has(Comment::factory()->count(50)->recycle($posts))
            ->has(Like::factory()->forEachSequence(
                ...$posts->random(100)->map(fn (Post $post) => ['likeable_id' => $post]),
            ))
            ->create([
                'name' => 'chang180',
                'email' => 'chang180@gmail.com',
            ]);
        }
    }
}
