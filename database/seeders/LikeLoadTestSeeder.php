<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\LazyCollection;
use function Laravel\Prompts\progress;

class LikeLoadTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $post = Post::find(1);

        $progress = progress(label: 'Creating likes for post 1', steps: 50_000);

        /** @var \Laravel\Prompts\Progress $progress */
        $progress->start();
        LazyCollection::times(500)->each(function () use ($post,$progress) {
            Like::factory(100)->for($post, 'likeable')->create();
            $progress->advance(100);
        });
        $progress->finish();

        $post->increment('likes_count', 50_000);
    }
}
