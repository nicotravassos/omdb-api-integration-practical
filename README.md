# omdb-api-integration-practical

## Setup Instructions

### Prerequisites

Before setting up the application, ensure you have the following installed on your system:

- **PHP** (version 8.0 or later)
- **Composer**
- **MySQL** (or any database supported by Laravel)

### Installation Steps

#### 1. Clone the Repository

```bash
git clone git@github.com:nicotravassos/omdb-api-integration-practical.git
cd omdb-api-integration-practical
```

#### 2. Install Dependencies

```bash
composer install
```

#### 3. Environment Configuration

Copy the .env.example file to create a new .env file:

```bash
cp .env.example .env
```

Open the .env file and set the following configurations:

Database settings: Update the database name, username, and password according to your local setup.

OMDB API Key: Add your OMDB API key to the `OMDB_API_KEY` environment variable:

```makefile
OMDB_API_KEY=your_api_key_here
```

#### 4. Database Setup

Run the following command to generate the application key:

```bash
php artisan key:generate
```

Create your database and configure the .env file with the appropriate database name, user, and password.

Run database migrations to set up the necessary tables:

```bash
php artisan migrate
```

#### 5. Run the Application

Start the Laravel development server using:

```bash
php artisan serve
```

By default, the application will be available at http://127.0.0.1:8000.

#### Usage Instructions

1. Search for Movies
   Navigate to the search page (default landing page): http://127.0.0.1:8000/movies/search.
   Enter a movie title in the search bar and click the "Search" button.
   The application will display a list of movies that match your query.
2. View Movie Details
   From the search results, click on the "View Details" button next to a movie.
   This will take you to a detailed page with more information about the selected movie.
3. View Trending Movies
   Navigate to the trending page to view popular movies based on search count: http://127.0.0.1:8000/movies/trending.

#### API Endpoints

The application exposes the following API endpoints:

1. Search Movies
   Endpoint:

```bash
GET /search?query={movie_title}
```

Use this endpoint to search for movies by title.
Replace {movie_title} with the title you want to search.

2. Movie Details
   Endpoint:

```bash
GET /movies/{imdbID}
```

Use this endpoint to fetch detailed information about a movie.
Replace {imdbID} with the IMDb ID of the movie.

3. Trending Movies
   Endpoint:

```bash
GET /trending
```

Use this endpoint to get a list of trending movies based on the search count.

#### Running Tests

You can run the test suite using PHPUnit. Ensure the development environment is set up properly and run:

```bash
php artisan test
```

This will execute all unit and feature tests, ensuring the application is working as expected.

#### Assumptions and Improvements

1. Assumptions

- The application assumes that the OMDB API key is valid and has the necessary access to fetch movie data.
- The database configuration in the .env file must be correctly set up before running migrations.
- The trending movies feature counts searches based on how often movies are searched via the application.

2. Potential Improvements

- Enhanced Error Handling:
    - Improve the error messages shown to users when the OMDB API fails.
    - Add retry mechanisms for API requests.

- Pagination for Search Results:
    - Introduce pagination for search results to improve user experience when there are many matches.

- Caching:
    - Cache movie details to reduce the number of API requests and improve performance.

- User Authentication:
    - Add user accounts and allow users to save favorite movies or track their search history.

- Responsive Enhancements:
    - Improve UI responsiveness and user experience on smaller screens.
