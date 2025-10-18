<header class="bg-gray-900">
  <nav aria-label="Global" class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8">
    <div class="flex lg:flex-1">
      <a href="#" class="-m-1.5 p-1.5">
        <span class="sr-only">Best Cine</span>
        <img src="{{ asset('images/icon.png') }}" alt="Logotipo Best Cine" class="h-10 w-auto" />
      </a>
    </div>
    <div class="flex lg:hidden">
      <button type="button" command="show-modal" commandfor="mobile-menu" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-400">
        <span class="sr-only">Open main menu</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
          <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>
    </div>
    <div class="hidden lg:flex lg:items-center lg:gap-x-12">
      <a href="{{ route("movies.index") }}" class="text-sm/6 font-semibold text-white">Filmes em Cartaz</a>
      <a href="#" class="text-sm/6 font-semibold text-white">Meus Ingressos</a>
    </div>
    <div class="hidden lg:flex lg:flex-1 lg:justify-end">
      @if(auth()->check())
        <div class="hidden sm:flex sm:items-center sm:ms-6">
          <x-dropdown align="right" width="48">
            <x-slot name="trigger">
              <button class="inline-flex items-center rounded-md border border-transparent px-3 py-2 text-sm font-medium leading-4 text-white hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                <div>{{ Auth::user()->name }}</div>
                <div class="ms-1">
                  <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </div>
              </button>
            </x-slot>

            <x-slot name="content">
              <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-dropdown-link :href="route('logout')"
                                 onclick="event.preventDefault(); this.closest('form').submit();">
                  {{ __('Log Out') }}
                </x-dropdown-link>
              </form>
            </x-slot>
          </x-dropdown>
        </div>
      @else
        <a href="{{ route('login') }}" class="text-sm/6 font-semibold text-white">Entrar <span aria-hidden="true">&rarr;</span></a>
      @endif
    </div>
  </nav>
</header>
