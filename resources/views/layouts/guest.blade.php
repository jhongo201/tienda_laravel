<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex">

            {{-- Panel izquierdo — branding --}}
            <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-600 flex-col justify-between p-12 overflow-hidden">
                {{-- Blobs decorativos --}}
                <div class="absolute -top-20 -right-20 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-10 w-72 h-72 bg-violet-400/10 rounded-full blur-2xl"></div>

                {{-- Logo --}}
                <a href="/" class="relative flex items-center gap-2.5 z-10">
                    <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-lg">{{ config('app.name') }}</span>
                </a>

                {{-- Contenido central --}}
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-6">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        <span class="text-indigo-200 text-sm font-medium">Tienda en línea</span>
                    </div>
                    <h2 class="text-4xl font-extrabold text-white leading-tight mb-4">
                        Tu destino de<br>
                        <span class="text-indigo-200">compras</span><br>
                        favorito.
                    </h2>
                    <p class="text-indigo-100 text-sm leading-relaxed max-w-sm">
                        Crea tu cuenta y accede a miles de productos con pagos seguros, envío rápido y las mejores ofertas.
                    </p>

                    {{-- Testimonial / Trust badges --}}
                    <div class="mt-10 grid grid-cols-3 gap-4">
                        @foreach([
                            ['n' => '10K+', 'l' => 'Clientes'],
                            ['n' => '99%', 'l' => 'Satisfacción'],
                            ['n' => '24/7', 'l' => 'Soporte'],
                        ] as $stat)
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 text-center border border-white/10">
                            <p class="text-white font-extrabold text-xl">{{ $stat['n'] }}</p>
                            <p class="text-indigo-200 text-xs mt-0.5">{{ $stat['l'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Footer brand --}}
                <p class="relative z-10 text-indigo-300 text-xs">
                    © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
                </p>
            </div>

            {{-- Panel derecho — formulario --}}
            <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 bg-gray-50">
                {{-- Logo móvil --}}
                <div class="lg:hidden mb-8">
                    <a href="/" class="flex items-center gap-2">
                        <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-900 text-lg">{{ config('app.name') }}</span>
                    </a>
                </div>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>

                <p class="mt-8 text-xs text-gray-400 text-center">
                    <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">← Volver a la tienda</a>
                </p>
            </div>

        </div>
        @livewireScripts
    </body>
</html>
