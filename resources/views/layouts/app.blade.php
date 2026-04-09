<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} — @yield('title', 'Tienda')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <!-- NAVBAR -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-600">
                    {{ config('app.name') }}
                </a>

                <!-- Nav links -->
                <div class="hidden md:flex items-center gap-6 text-sm">
                    <a href="{{ route('home') }}"
                       class="text-gray-600 hover:text-indigo-600 transition">Inicio</a>
                    <a href="{{ route('catalog') }}"
                       class="text-gray-600 hover:text-indigo-600 transition">Productos</a>
                </div>

                <!-- Acciones -->
                <div class="flex items-center gap-4">
                    {{-- Carrito con drawer Livewire --}}
                    @livewire('cart-drawer')

                    @auth
                        <div class="flex items-center gap-3 text-sm">
                            <a href="{{ route('dashboard') }}"
                               class="hidden md:flex items-center gap-1.5 text-gray-600 hover:text-indigo-600 transition font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0M19 21a7 7 0 10-14 0"/>
                                </svg>
                                {{ auth()->user()->name }}
                            </a>
                            @role('admin')
                                <a href="{{ route('admin.dashboard') }}"
                                   class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700 transition text-xs font-medium">
                                    Admin
                                </a>
                            @endrole
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="text-gray-500 hover:text-red-500 transition text-xs">
                                    Salir
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm text-gray-600 hover:text-indigo-600 transition">
                            Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}"
                           class="bg-indigo-600 text-white px-4 py-1.5 rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                            Registrarse
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </nav>

    <!-- CONTENIDO -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 py-8 text-center text-sm text-gray-400">
            © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
        </div>
    </footer>

    @livewireScripts
</body>
</html>