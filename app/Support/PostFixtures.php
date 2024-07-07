<?php
namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class PostFixtures
{
    private static ?Collection $fixtures = null;

    public static function getFixtures(): Collection
    {
        if (is_null(self::$fixtures)) {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://graph.imdbapi.dev/v1', [
                'query' => 'query { titles(ids: ["tt0111161", "tt0068646", "tt0071562", "tt0468569", "tt0050083", "tt0108052", "tt0167260", "tt0110912", "tt0060196", "tt0137523", "tt0120737", "tt0080684", "tt0109830", "tt1375666", "tt0167261", "tt0133093", "tt0099685", "tt0073486", "tt0047478", "tt0114369", "tt0317248", "tt0118799", "tt0076759", "tt0102926", "tt0038650", "tt0120815", "tt0114814", "tt0245429", "tt0110413", "tt0120689"]) { id primary_title rating { aggregate_rating } genres plot } }'
            ]);

            $data = $response->json();

            if (!isset($data['data']['titles'])) {
                self::$fixtures = collect();
                return self::$fixtures;
            }

            self::$fixtures = collect($data['data']['titles'])->map(function ($movie) {
                return [
                    'title' => $movie['primary_title'] ?? 'N/A',
                    'body' => self::generateBody($movie),
                ];
            });
        }

        return self::$fixtures;
    }

    private static function generateBody($movie): string
    {
        $rating = $movie['rating']['aggregate_rating'] ?? 'N/A';
        $genres = !empty($movie['genres']) ? implode(', ', $movie['genres']) : 'N/A';
        $plot = $movie['plot'] ?? 'N/A';

        return "# {$movie['primary_title']}\n\n"
             . "## 評分: $rating\n\n"
             . "### 類型: $genres\n\n"
             . "#### 簡介:\n$plot\n";
    }
}
