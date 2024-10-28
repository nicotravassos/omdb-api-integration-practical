<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\MovieController;
use App\Models\TrendingMovie;
use App\Services\MovieService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class MovieControllerTest extends TestCase
{
    /**
     * Test the search movies endpoint (success case).
     */
    public function testSearchSuccess()
    {
        $movieService = Mockery::mock(MovieService::class, function (MockInterface $mock) {
            $mock->shouldReceive('searchMovies')
                ->once()
                ->with('shawshank')
                ->andReturn(['Search' => [['Title' => 'The Shawshank Redemption']]]);
        });

        $controller = new MovieController($movieService);

        $request = Request::create('/search', 'GET', ['query' => 'shawshank']);
        $response = $controller->search($request);

        $this->assertInstanceOf(View::class, $response);
        $this->assertArrayHasKey('movies', $response->getData());
        $this->assertEquals('The Shawshank Redemption', $response->getData()['movies']['Search'][0]['Title']);
    }

    /**
     * Test the search movies endpoint (failure case).
     */
    public function testSearchFailure()
    {
        $movieService = Mockery::mock(MovieService::class, function (MockInterface $mock) {
            $mock->shouldReceive('searchMovies')
                ->once()
                ->with('unknown')
                ->andReturn(null);
        });

        $controller = new MovieController($movieService);

        $request = Request::create('/search', 'GET', ['query' => 'unknown']);
        $response = $controller->search($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertTrue(session()->has('errors'));
    }

    /**
     * Test the details endpoint (success case).
     */
    public function testDetailsSuccess()
    {
        $movieService = Mockery::mock(MovieService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getMovieDetails')
                ->once()
                ->with('tt0111161')
                ->andReturn(['Title' => 'The Shawshank Redemption']);
        });

        $controller = new MovieController($movieService);
        $response = $controller->details('tt0111161');

        $this->assertInstanceOf(View::class, $response);

        $this->assertArrayHasKey('movie', $response->getData());

        $this->assertEquals('The Shawshank Redemption', $response->getData()['movie']['Title']);
    }

    /**
     * Test the details endpoint (failure case).
     */
    public function testDetailsFailure()
    {
        $movieService = Mockery::mock(MovieService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getMovieDetails')
                ->once()
                ->with('invalid_id')
                ->andReturn(null);
        });

        $controller = new MovieController($movieService);
        $response = $controller->details('invalid_id');

        $this->assertTrue(session()->has('errors'));
    }

    /**
     * Test the trending movies endpoint (success case).
     */
    public function testTrendingMoviesSuccess()
    {
        // Ensure the TrendingMovie model is imported correctly.
        $movieService = Mockery::mock(MovieService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getTrendingMovies')
                ->once()
                ->andReturn(TrendingMovie::factory()->count(3)->make());
        });

        $controller = new MovieController($movieService);
        $response = $controller->trending();

        $this->assertInstanceOf(View::class, $response);

        $this->assertArrayHasKey('trendingMovies', $response->getData());

        $this->assertCount(3, $response->getData()['trendingMovies']);
    }

    /**
     * Test the trending movies endpoint (failure case).
     */
    public function testTrendingMoviesFailure()
    {
        $movieService = Mockery::mock(MovieService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getTrendingMovies')
                ->once()
                ->andReturn(TrendingMovie::query()->where('id', '<', 0)->get());
        });

        $controller = new MovieController($movieService);
        $response = $controller->trending();

        $this->assertInstanceOf(View::class, $response);

        $this->assertArrayHasKey('trendingMovies', $response->getData());

        $this->assertEmpty($response->getData()['trendingMovies']);
    }
}
