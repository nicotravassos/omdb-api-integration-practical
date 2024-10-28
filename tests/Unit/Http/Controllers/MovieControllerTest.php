<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\MovieController;
use App\Services\MovieService;
use Illuminate\Http\Request;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class MovieControllerTest extends TestCase
{
//    public function testSearchSuccess()
//    {
//        $movieService = Mockery::mock(MovieService::class, function (MockInterface $mock) {
//            $mock->shouldReceive('searchMovies')
//                ->once()
//                ->with('shawshank')
//                ->andReturn(['Search' => [['Title' => 'The Shawshank Redemption']]]);
//        });
//
//        $controller = new MovieController($movieService);
//
//        $request = Request::create('/search', 'GET', ['query' => 'shawshank']);
//        $response = $controller->search($request);
//
//        $this->assertTrue(View::has('movies'));
//        $this->assertEquals(200, $response->getStatusCode());
//    }

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

        $this->assertTrue(session()->has('errors'));
    }

//    public function testDetailsSuccess()
//    {
//        $movieService = Mockery::mock(MovieService::class, function (MockInterface $mock) {
//            $mock->shouldReceive('getMovieDetails')
//                ->once()
//                ->with('tt0111161')
//                ->andReturn(['Title' => 'The Shawshank Redemption']);
//        });
//
//        $controller = new MovieController($movieService);
//        $response = $controller->details('tt0111161');
//
//        $this->assertTrue(View::has('movie'));
//        $this->assertEquals(200, $response->getStatusCode());
//    }

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

//    public function testTrendingMovies()
//    {
//        $movieService = Mockery::mock(MovieService::class, function (MockInterface $mock) {
//            $mock->shouldReceive('getTrendingMovies')
//                ->once()
//                ->andReturn(TrendingMovie::factory()->count(3)->make());
//        });
//
//        $controller = new MovieController($movieService);
//        $response = $controller->trending();
//
//        $this->assertTrue(View::has('trendingMovies'));
//        $this->assertEquals(200, $response->getStatusCode());
//    }
}
