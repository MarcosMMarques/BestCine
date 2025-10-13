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
    <el-popover-group class="hidden lg:flex lg:gap-x-12">
      <a href="#" class="text-sm/6 font-semibold text-white">Filmes em Cartaz</a>
      <a href="#" class="text-sm/6 font-semibold text-white">Meus Ingressos</a>
    </el-popover-group>
    <div class="hidden lg:flex lg:flex-1 lg:justify-end">
      <a href="#" class="text-sm/6 font-semibold text-white">Entrar <span aria-hidden="true">&rarr;</span></a>
    </div>
  </nav>
</header>
