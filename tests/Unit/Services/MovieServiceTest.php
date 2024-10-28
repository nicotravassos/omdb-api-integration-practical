<?php

namespace Tests\Unit\Services;

use App\Exceptions\ExternalApiException;
use App\Models\TrendingMovie;
use App\Services\MovieService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MovieServiceTest extends TestCase
{
    protected MovieService $movieService;

    /**
     * Test searching for movies (success case).
     * Ensures that the service correctly fetches and returns movie search results.
     */
    public function testSearchMoviesSuccess()
    {
        Http::fake([
            '*' => Http::response(['Search' => [['imdbID' => 'tt0111161', 'Title' => 'The Shawshank Redemption']]], 200)
        ]);

        $result = $this->movieService->searchMovies('shawshank');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('Search', $result);
    }

    /**
     * Test searching for movies with an invalid API key.
     * Verifies that the service throws an ExternalApiException for a 401 response.
     */
    public function testSearchMoviesInvalidApiKey()
    {
        Http::fake([
            '*' => Http::response(null, 401)
        ]);

        $this->expectException(ExternalApiException::class);
        $this->expectExceptionMessage("Invalid API key. Please verify your settings.");

        $this->movieService->searchMovies('shawshank');
    }

    /**
     * Test searching for movies when rate limit is exceeded.
     * Confirms that the service handles a 429 response by throwing an ExternalApiException.
     */
    public function testSearchMoviesRateLimitExceeded()
    {
        Http::fake([
            '*' => Http::response(null, 429)
        ]);

        $this->expectException(ExternalApiException::class);
        $this->expectExceptionMessage("Rate limit exceeded. Please wait before trying again.");

        $this->movieService->searchMovies('shawshank');
    }

    /**
     * Test searching for movies when an unexpected error occurs.
     * Ensures that a 500 response triggers an ExternalApiException with a proper message.
     */
    public function testSearchMoviesUnexpectedError()
    {
        Http::fake([
            '*' => Http::response(null, 500)
        ]);

        $this->expectException(ExternalApiException::class);
        $this->expectExceptionMessage("An unexpected error occurred. Please try again.");

        $this->movieService->searchMovies('shawshank');
    }

    /**
     * Test fetching movie details (success case).
     * Verifies that the service correctly retrieves and returns movie details.
     */
    public function testGetMovieDetailsSuccess()
    {
        Http::fake([
            '*' => Http::response(['Title' => 'The Shawshank Redemption', 'imdbID' => 'tt0111161'], 200)
        ]);

        $result = $this->movieService->getMovieDetails('tt0111161');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('Title', $result);
        $this->assertEquals('The Shawshank Redemption', $result['Title']);
    }

    /**
     * Test retrieving trending movies.
     * Confirms that the service fetches trending movies from the database correctly.
     */
    public function testGetTrendingMovies()
    {
        TrendingMovie::factory()->create(['title' => 'The Shawshank Redemption', 'search_count' => 5]);

        $trendingMovies = $this->movieService->getTrendingMovies();

        $this->assertNotEmpty($trendingMovies);
        $this->assertEquals('The Shawshank Redemption', $trendingMovies->first()->title);
    }

    /**
     * Set up the test environment.
     * Initializes the MovieService instance for use in each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->movieService = new MovieService();
    }
}
