<?php

namespace App\Services;

use App\Models\TrendingMovie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MovieService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OMDB_API_KEY');
    }

    public function searchMovies(string $query)
    {
        $response = Http::get('http://www.omdbapi.com/', [
            'apikey' => $this->apiKey,
            's' => $query
        ]);

        if ($response->successful()) {
            $movies = $response->json();

            if (isset($movies['Search'])) {
                foreach ($movies['Search'] as $movie) {
                    $this->logSearch($movie['imdbID'], $movie['Title']);
                }
            }

            return $movies;
        }

        return null;
    }

    private function logSearch(string $imdbId, string $title)
    {
        DB::transaction(function () use ($imdbId, $title) {
            $trendingMovie = TrendingMovie::firstOrCreate(
                ['imdb_id' => $imdbId],
                ['title' => $title]
            );

            $trendingMovie->increment('search_count');
        });
    }

    public function getMovieDetails(string $id)
    {
        $response = Http::get('http://www.omdbapi.com/', [
            'apikey' => $this->apiKey,
            'i' => $id
        ]);

        return $response->successful() ? $response->json() : null;
    }

    public function getTrendingMovies()
    {
        return TrendingMovie::orderBy('search_count', 'desc')->take(10)->get();
    }
}
