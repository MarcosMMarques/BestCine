@extends('layouts.app')

@section('content')
  <div class="container mx-auto px-4">

    <h2 class="text-3xl font-bold text-center mb-8">Em cartaz</h2>

    @if($movies)
      <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">

        @foreach ($movies as $movie)
            <div class="w-full max-w-xs overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-800">
                <img class="object-cover w-full h-56" src="https://image.tmdb.org/t/p/w500{{$movie['poster_path']}}" alt="avatar">

                <div class="py-5 text-center">
                    <a href="#" class="block text-xl font-bold text-gray-800 dark:text-white" tabindex="0" role="link">{{ $movie['title'] }}</a>
                    <span class="text-sm text-gray-700 dark:text-gray-200">{{ $movie['release_date'] }}</span>
                </div>
            </div>
        @endforeach

      </div>
    @else
      <div class="text-center text-gray-500">
        <p>Não há filmes em cartaz</p>
      </div>
    @endif

  </div>
@endsection
