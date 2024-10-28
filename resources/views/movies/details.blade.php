<x-guest-layout>
    <div class="bg-gray-800 text-white shadow-xl rounded-lg max-w-5xl w-full overflow-hidden">
        <div class="p-8">
            <h1 class="text-4xl font-bold text-center text-white mb-8">Movie Details</h1>

            @if(session('errors'))
                <div class="bg-red-600 text-white p-4 rounded mb-6">
                    {{ session('errors')->first('error') }}
                </div>
            @endif

            @if(!empty($movie))
                <div class="flex flex-col md:flex-row items-center md:items-start">
                    <div class="flex-shrink-0">
                        <img src="{{ $movie['Poster'] }}" alt="{{ $movie['Title'] }} Poster"
                             class="w-64 h-auto rounded-lg shadow-lg mb-6 md:mb-0">
                    </div>

                    <div class="md:ml-8 flex-1 text-white">
                        <h2 class="text-3xl font-semibold">{{ $movie['Title'] }} <span
                                class="text-gray-400 text-xl">({{ $movie['Year'] }})</span></h2>
                        <div class="mt-4">
                            <p class="mb-2"><strong class="font-bold">Director:</strong> {{ $movie['Director'] }}</p>
                            <p class="mb-2"><strong class="font-bold">Genre:</strong> {{ $movie['Genre'] }}</p>
                            <p class="mt-4 text-gray-300 leading-relaxed">{{ $movie['Plot'] }}</p>
                            <p class="mt-4"><strong class="font-bold">Actors:</strong> {{ $movie['Actors'] }}</p>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-center text-gray-400">Movie details are not available.</p>
            @endif

            <div class="text-center mt-10">
                <a href="{{ route('movies.search') }}"
                   class="inline-block bg-blue-600 text-white py-3 px-8 rounded-full shadow-lg hover:bg-blue-500 transition duration-300 transform hover:scale-105">Back
                    to Search</a>
            </div>
        </div>
    </div>
</x-guest-layout>
