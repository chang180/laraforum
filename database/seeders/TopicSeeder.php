<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'slug' => 'general',
                'name' => 'General',
                'description' => 'General discussion'
            ],
            [
                'slug' => 'help',
                'name' => 'Help',
                'description' => 'Get help with your code'
            ],
            [
                'slug' => 'showcase',
                'name' => 'Showcase',
                'description' => 'Show off your projects'
            ],
            [
                'slug' => 'off-topic',
                'name' => 'Off Topic',
                'description' => 'General discussion'
            ]
        ];

        Topic::upsert($data, ['slug']);
    }
}
