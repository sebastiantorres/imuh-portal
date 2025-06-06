<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Portal del Ciudadano</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">

  {{-- HEADER --}}
  <header class="header-gradient bg-design2 bg-cover bg-center h-16 flex items-center justify-between px-6">
    {{-- Logo + título --}}
    <div class="flex items-center space-x-2">
      <img src="{{ asset('images/logo-imuh.jpg') }}" alt="Logo" class="h-8 w-auto" onerror="this.style.display='none';">
      <h1 class="text-primary text-xl font-bold">Portal del Ciudadano</h1>
    </div>

    {{-- Menú de usuario --}}
    @if(session()->has('portal_user'))
      <div class="header-user relative inline-block">
        {{-- Botón con nombre tomado de session('portal_user') --}}
        <button class="flex items-center space-x-1 focus:outline-none">
          <span class="text-secondary font-medium">
            {{ session('portal_user')['name'] ?? 'Usuario' }}
          </span>
          <svg class="w-4 h-4 text-secondary transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>

        {{-- Dropdown oculto/visible solo con hover en .header-user --}}
        <div class="dropdown absolute right-0 mt-1 w-40 bg-white rounded-md shadow-lg z-10">
           <a href="{{ route('portal.dashboard') }}"
              class="block px-4 py-2 text-sm text-secondary hover:bg-gray-100 hover:underline">
              Panel de control
          </a>
          <a href="{{ route('portal.account') }}"
              class="block px-4 py-2 text-sm text-secondary hover:bg-gray-100 hover:underline">
              Mi cuenta
          </a>
          <a href="#"
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
             class="block px-4 py-2 text-sm text-secondary hover:underline">
            Cerrar sesión
          </a>
        </div>

        {{-- Formulario oculto que dispara tu AuthController@logout --}}
        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
          @csrf
        </form>
      </div>

    @endif

  </header>

  <main class="container mx-auto py-6 px-6">
    @yield('content')
  </main>

  <footer class="text-center py-4 text-gray-600">
    © 2025 Portal del Ciudadano
  </footer>

</body>
</html>
