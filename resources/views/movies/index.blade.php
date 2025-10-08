
<section class="bg-gray-900 text-white py-12">
  <div class="container mx-auto px-4">

    <h2 class="text-3xl font-bold text-center mb-8">Now Showing</h2>

    @if($movies)
      <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">

        @foreach ($movies as $movie)
          <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg group transform transition-transform duration-300 hover:-translate-y-2">
            <a href="#">
              <img
                src="https://image.tmdb.org/t/p/w500{{$movie['poster_path']}}"
                alt="Poster of {{ $movie['title']}}"
                class="w-full h-auto object-cover aspect-[2/3] transform group-hover:scale-105 transition-transform duration-300"
              >
            </a>

            <div class="p-4">
              <h3 class="text-lg font-semibold truncate" title="{{ $movie['title']}}">
                {{ $movie['title']}}
              </h3>
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
</section>

