<x-guest-layout>
    <div class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-4xl p-6">
            <h1 class="text-3xl font-bold text-center mb-8">Movie Search Results</h1>

            <form action="{{ route('movies.search') }}" method="GET" class="flex justify-center mb-6">
                <input
                    type="text"
                    name="query"
                    placeholder="Search for a movie..."
                    class="w-full md:w-2/3 p-3 rounded-l-lg bg-gray-700 text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-600"
                    required
                />
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 px-6 rounded-r-lg transition duration-300">
                    Search
                </button>
            </form>

            @if(session('errors'))
                <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
                    {{ session('errors')->first('error') }}
                </div>
            @endif

            @if(!empty($movies['Search']))
                <ul class="space-y-4">
                    @foreach($movies['Search'] as $movie)
                        <li class="bg-gray-700 rounded-lg p-4 hover:bg-gray-600 transition duration-300">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-xl font-bold">{{ $movie['Title'] }} <span class="text-gray-400">({{ $movie['Year'] }})</span>
                                    </h2>
                                </div>
                                <a href="{{ route('movies.details', ['id' => $movie['imdbID']]) }}"
                                   class="text-blue-500 hover:text-blue-400 font-semibold transition duration-300">
                                    View Details
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-center text-gray-400 mt-6">No movies found. Please try a different search.</p>
            @endif
        </div>
    </div>
</x-guest-layout>
