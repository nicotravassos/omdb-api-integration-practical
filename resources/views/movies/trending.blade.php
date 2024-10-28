<x-guest-layout>
    <div class="bg-gray-800 text-white shadow-xl rounded-lg max-w-5xl w-full p-8">
        <h1 class="text-4xl font-bold text-center mb-8">Trending Movies</h1>

        @if(!empty($trendingMovies) && $trendingMovies->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($trendingMovies as $movie)
                    <div
                        class="bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition duration-300">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold mb-2">{{ $movie->title }}</h2>
                            <p class="text-gray-400">Search Count: <span
                                    class="font-bold text-gray-300">{{ $movie->search_count }}</span></p>
                            <a href="{{ route('movies.details', ['id' => $movie->imdb_id]) }}"
                               class="inline-block mt-4 bg-blue-600 text-white py-2 px-4 rounded-full hover:bg-blue-500 transition duration-300 transform hover:scale-105">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-400">No trending movies available.</p>
        @endif

        <div class="text-center mt-10">
            <a href="{{ route('movies.search') }}"
               class="inline-block bg-blue-600 text-white py-3 px-8 rounded-full shadow-lg hover:bg-blue-500 transition duration-300 transform hover:scale-105">
                Search for Movies
            </a>
        </div>
    </div>
</x-guest-layout>
