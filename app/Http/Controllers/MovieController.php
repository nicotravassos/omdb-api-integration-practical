<?php

namespace App\Http\Controllers;

use App\Services\MovieService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * The service instance used to interact with the movie data.
     *
     * @var MovieService
     */
    protected MovieService $movieService;

    /**
     * MovieController constructor.
     *
     * @param MovieService $movieService
     */
    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * Search for movies based on a query string.
     *
     * @param Request $request
     * @return View|Application|Factory|RedirectResponse
     */
    public function search(Request $request): View|Application|Factory|RedirectResponse
    {
        $query = $request->input('query');
        $movies = $this->movieService->searchMovies($query);

        if ($movies) {
            return view('movies.search', compact('movies'));
        }

        return back()->withErrors(['error' => 'Unable to fetch data']);
    }

    /**
     * Display the details of a specific movie by its IMDb ID.
     *
     * @param string $id
     * @return View|Application|Factory|RedirectResponse
     */
    public function details(string $id): View|Application|Factory|RedirectResponse
    {
        $movie = $this->movieService->getMovieDetails($id);

        if ($movie) {
            return view('movies.details', compact('movie'));
        }

        return back()->withErrors(['error' => 'Unable to fetch data']);
    }

    /**
     * Show a list of trending movies based on search counts.
     *
     * @return View|Factory|Application
     */
    public function trending(): View|Factory|Application
    {
        $trendingMovies = $this->movieService->getTrendingMovies();
        return view('movies.trending', compact('trendingMovies'));
    }
}
