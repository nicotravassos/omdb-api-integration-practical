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
    protected MovieService $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function search(Request $request): View|Application|Factory|RedirectResponse
    {
        $query = $request->input('query');
        $movies = $this->movieService->searchMovies($query);

        if ($movies) {
            return view('movies.search', compact('movies'));
        }

        return back()->withErrors(['error' => 'Unable to fetch data']);
    }

    public function details(string $id): View|Application|Factory|RedirectResponse
    {
        $movie = $this->movieService->getMovieDetails($id);

        if ($movie) {
            return view('movies.details', compact('movie'));
        }

        return back()->withErrors(['error' => 'Unable to fetch data']);
    }

    public function trending(): View|Factory|Application
    {
        $trendingMovies = $this->movieService->getTrendingMovies();
        return view('movies.trending', compact('trendingMovies'));
    }
}
