<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MovieService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieApiController extends Controller
{
    protected MovieService $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * Search movies based on a query.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->query('query');

        if (!$query) {
            return response()->json(['error' => 'Query parameter is required'], 400);
        }

        $movies = $this->movieService->searchMovies($query);

        if ($movies && isset($movies['Search'])) {
            return response()->json($movies['Search'], 200);
        }

        return response()->json(['error' => 'Unable to fetch data or no movies found'], 404);
    }

    /**
     * Get movie details by IMDB ID.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function details(string $id): JsonResponse
    {
        $movie = $this->movieService->getMovieDetails($id);

        if ($movie) {
            return response()->json($movie, 200);
        }

        return response()->json(['error' => 'Unable to fetch data or movie not found'], 404);
    }

    /**
     * Get trending movies.
     *
     * @return JsonResponse
     */
    public function trending(): JsonResponse
    {
        $trendingMovies = $this->movieService->getTrendingMovies();

        if ($trendingMovies->isEmpty()) {
            return response()->json(['error' => 'No trending movies found'], 404);
        }

        return response()->json($trendingMovies, 200);
    }
}
