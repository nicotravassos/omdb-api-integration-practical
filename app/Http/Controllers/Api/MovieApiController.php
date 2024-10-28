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

        if ($movies) {
            return response()->json($movies);
        }

        return response()->json(['error' => 'Unable to fetch data'], 500);
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
            return response()->json($movie);
        }

        return response()->json(['error' => 'Unable to fetch data'], 500);
    }

    /**
     * Get trending movies.
     *
     * @return JsonResponse
     */
    public function trending(): JsonResponse
    {
        $trendingMovies = $this->movieService->getTrendingMovies();

        return response()->json($trendingMovies);
    }
}
