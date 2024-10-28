<?php

namespace App\Services;

use App\Exceptions\ExternalApiException;
use App\Models\TrendingMovie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MovieService
{
    protected string $apiKey;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = env('OMDB_API_KEY');
        $this->apiUrl = config('services.omdb.api_url', 'http://www.omdbapi.com/');
    }

    /**
     * Search movies based on a query.
     *
     * @param string $query
     * @return array|null
     */
    public function searchMovies(string $query): ?array
    {
        $response = $this->makeApiRequest([
            's' => $query
        ]);

        if (isset($response['Search'])) {
            $this->logSearchResults($response['Search']);
        }

        return $response;
    }

    /**
     * Make an API request and handle responses.
     *
     * @param array $params
     * @return array|null
     */
    private function makeApiRequest(array $params): ?array
    {
        try {
            $response = Http::get($this->apiUrl, array_merge($params, [
                'apikey' => $this->apiKey
            ]));

            if ($response->successful()) {
                return $response->json();
            }

            return $this->handleApiError($response);

        } catch (RequestException $e) {
            Log::error("API request failed: {$e->getMessage()}");
            throw new ExternalApiException("Unable to process the request. Please try again later.");
        }
    }

    /**
     * Handle API errors and provide user-friendly messages.
     *
     * @param \Illuminate\Http\Client\Response $response
     * @return array
     */
    private function handleApiError($response): array
    {
        switch ($response->status()) {
            case 401:
                throw new ExternalApiException("Invalid API key. Please verify your settings.");
            case 429:
                throw new ExternalApiException("Rate limit exceeded. Please wait before trying again.");
            default:
                throw new ExternalApiException("An unexpected error occurred. Please try again.");
        }
    }

    /**
     * Log search results to the trending movies.
     *
     * @param array $movies
     * @return void
     */
    private function logSearchResults(array $movies): void
    {
        DB::transaction(function () use ($movies) {
            foreach ($movies as $movie) {
                $trendingMovie = TrendingMovie::firstOrCreate(
                    ['imdb_id' => $movie['imdbID']],
                    ['title' => $movie['Title']]
                );

                $trendingMovie->increment('search_count');
            }
        });
    }

    /**
     * Get movie details by ID.
     *
     * @param string $id
     * @return array|null
     */
    public function getMovieDetails(string $id): ?array
    {
        $response = $this->makeApiRequest(['i' => $id]);

        if (isset($response['Error'])) {
            return null;
        }

        return $response;
    }

    /**
     * Retrieve trending movies based on search counts.
     *
     * @return Collection
     */
    public function getTrendingMovies(): Collection
    {
        return TrendingMovie::orderBy('search_count', 'desc')->take(10)->get();
    }
}
