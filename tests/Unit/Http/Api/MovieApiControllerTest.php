<?php

namespace Tests\Unit\Http\Controllers\Api;

use App\Http\Controllers\Api\MovieApiController;
use App\Services\MovieService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class MovieApiControllerTest extends TestCase
{
    /**
     * Test the search movies endpoint (success case).
     */
    public function testSearchMoviesSuccess()
    {
        $movieService = Mockery::mock(MovieService::class, function (MockInterface $mock) {
            $mock->shouldReceive('searchMovies')
                ->once()
                ->with('inception')
                ->andReturn(['Search' => [['Title' => 'Inception']]]);
        });

        $controller = new MovieApiController($movieService);
        $request = Request::create('/search', 'GET', ['query' => 'inception']);
        $response = $controller->search($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('Search', $response->getData(true));
    }

    /**
     * Test the search movies endpoint (missing query parameter).
     */
    public function testSearchMoviesMissingQuery()
    {
        $movieService = Mockery::mock(MovieService::class);
        $controller = new MovieApiController($movieService);
        $request = Request::create('/search', 'GET', []);

        $response = $controller->search($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Query parameter is required', $response->getData(true)['error']);
    }

    /**
     * Test the search movies endpoint (failure case).
     */
    public function testSearchMoviesFailure()
    {
        $movieService = Mockery::mock(MovieService::class, function (MockInterface $mock) {
            $mock->shouldReceive('searchMovies')
                ->once()
                ->with('unknown')
                ->andReturn(null);
        });

        $controller = new MovieApiController($movieService);
        $request = Request::create('/search', 'GET', ['query' => 'unknown']);
        $response = $controller->search($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('Unable to fetch data', $response->getData(true)['error']);
    }

    /**
     * Test the get movie details endpoint (success case).
     */
    public function testGetMovieDetailsSuccess()
    {
        $movieService = Mockery::mock(MovieService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getMovieDetails')
                ->once()
                ->with('tt1375666')
                ->andReturn(['Title' => 'Inception']);
        });

        $controller = new MovieApiController($movieService);
        $response = $controller->details('tt1375666');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('Title', $response->getData(true));
    }

    /**
     * Test the get movie details endpoint (failure case).
     */
    public function testGetMovieDetailsFailure()
    {
        $movieService = Mockery::mock(MovieService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getMovieDetails')
                ->once()
                ->with('invalid_id')
                ->andReturn(null);
        });

        $controller = new MovieApiController($movieService);
        $response = $controller->details('invalid_id');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('Unable to fetch data', $response->getData(true)['error']);
    }

    /**
     * Test the get trending movies endpoint.
     */
    public function testGetTrendingMovies()
    {
        $movieService = Mockery::mock(MovieService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getTrendingMovies')
                ->once()
                ->andReturn(\App\Models\TrendingMovie::factory()->count(3)->make());
        });

        $controller = new MovieApiController($movieService);
        $response = $controller->trending();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsArray($response->getData(true));
        $this->assertNotEmpty($response->getData(true));
    }
}
