@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

{{-- ═══════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-600">
    {{-- Decorative blobs --}}
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 -left-16 w-72 h-72 bg-violet-400/10 rounded-full blur-2xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="max-w-2xl">
            <span class="inline-flex items-center gap-1.5 bg-white/15 text-white text-xs font-semibold px-3 py-1.5 rounded-full mb-6 backdrop-blur-sm">
                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                Nuevos productos disponibles
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight tracking-tight">
                Todo lo que<br>
                <span class="text-indigo-200">necesitas,</span><br>
                en un solo lugar.
            </h1>
            <p class="mt-6 text-lg text-indigo-100 leading-relaxed max-w-lg">
                Descubre miles de productos con la mejor calidad, envío rápido y pagos 100% seguros a través de Wompi.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('catalog') }}"
                   class="inline-flex items-center justify-center gap-2 bg-white text-indigo-700 px-7 py-3.5 rounded-xl font-bold text-sm hover:bg-indigo-50 transition shadow-lg shadow-indigo-900/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Ver catálogo
                </a>
                @guest
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center gap-2 bg-indigo-500/40 text-white border border-white/25 px-7 py-3.5 rounded-xl font-bold text-sm hover:bg-indigo-500/60 transition backdrop-blur-sm">
                    Crear cuenta gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
                @endguest
            </div>
        </div>
    </div>

    {{-- Wave bottom --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0 48h1440V24C1200 8 960 0 720 0S240 8 0 24v24z" fill="#f9fafb"/>
        </svg>
    </div>
</section>

{{-- ═══════════════════════════════════════════════
     BENEFITS BAR
═══════════════════════════════════════════════ --}}
<section class="bg-gray-50 pt-10 pb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach([
                ['icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'title' => 'Envío seguro', 'desc' => 'A todo el país'],
                ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Pago seguro', 'desc' => 'Wompi certificado'],
                ['icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'title' => 'Devoluciones', 'desc' => '30 días sin preguntas'],
                ['icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'title' => 'Soporte 24/7', 'desc' => 'Siempre disponibles'],
            ] as $benefit)
            <div class="flex items-center gap-3 bg-white rounded-2xl px-5 py-4 shadow-sm border border-gray-100">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $benefit['icon'] }}"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-gray-900 text-sm">{{ $benefit['title'] }}</p>
                    <p class="text-xs text-gray-400">{{ $benefit['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════
     CATEGORÍAS
═══════════════════════════════════════════════ --}}
@if($categories->isNotEmpty())
<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500 mb-1">Explora</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Compra por categoría</h2>
            </div>
            <a href="{{ route('catalog') }}" class="hidden sm:inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">
                Ver todo
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        @php
            $catColors = [
                'from-indigo-100 to-indigo-50 text-indigo-600',
                'from-violet-100 to-violet-50 text-violet-600',
                'from-emerald-100 to-emerald-50 text-emerald-600',
                'from-amber-100 to-amber-50 text-amber-600',
                'from-rose-100 to-rose-50 text-rose-600',
                'from-sky-100 to-sky-50 text-sky-600',
            ];
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $i => $cat)
                @php $colors = $catColors[$i % count($catColors)]; @endphp
                <a href="{{ route('catalog', ['category' => $cat->slug]) }}"
                   class="group relative bg-gradient-to-br {{ $colors }} rounded-2xl p-6 text-center hover:-translate-y-1 transition-all duration-200 hover:shadow-lg">
                    <div class="w-12 h-12 bg-white/70 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-sm group-hover:scale-110 transition-transform duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                        </svg>
                    </div>
                    <p class="font-bold text-sm leading-tight">{{ $cat->name }}</p>
                    <p class="text-xs opacity-70 mt-1">{{ $cat->products_count }} productos</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════
     PRODUCTOS DESTACADOS
═══════════════════════════════════════════════ --}}
@if($featured->isNotEmpty())
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500 mb-1">Lo más nuevo</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Productos destacados</h2>
            </div>
            <a href="{{ route('catalog') }}" class="hidden sm:inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">
                Ver catálogo completo
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featured as $product)
                <a href="{{ route('product.show', $product->slug) }}"
                   class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl hover:border-indigo-100 transition-all duration-200 hover:-translate-y-1">

                    {{-- Thumbnail --}}
                    <div class="relative aspect-[4/3] bg-gradient-to-br from-gray-50 to-indigo-50 overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-16 h-16 text-indigo-100 group-hover:text-indigo-200 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                            </svg>
                        </div>
                        @if($product->stock <= 5 && $product->stock > 0)
                            <span class="absolute top-3 left-3 bg-amber-400 text-white text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm">
                                ¡Últimas unidades!
                            </span>
                        @elseif($product->stock === 0)
                            <span class="absolute top-3 left-3 bg-gray-400 text-white text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm">
                                Agotado
                            </span>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-4">
                        @if($product->category)
                            <span class="text-xs font-semibold text-indigo-500 uppercase tracking-wide">
                                {{ $product->category->name }}
                            </span>
                        @endif
                        <h3 class="font-bold text-gray-900 mt-1 text-sm leading-snug line-clamp-2 group-hover:text-indigo-600 transition-colors">
                            {{ $product->name }}
                        </h3>
                        <div class="mt-3 flex items-center justify-between">
                            <div>
                                <span class="text-xl font-extrabold text-gray-900">
                                    ${{ number_format($product->base_price, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-gray-400 ml-1">COP</span>
                            </div>
                            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center group-hover:bg-indigo-700 transition-colors shadow-sm">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-10 text-center sm:hidden">
            <a href="{{ route('catalog') }}"
               class="inline-flex items-center gap-2 bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold text-sm hover:bg-indigo-700 transition">
                Ver todos los productos
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════
     CTA FINAL
═══════════════════════════════════════════════ --}}
<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative bg-gradient-to-br from-indigo-600 to-violet-600 rounded-3xl overflow-hidden px-8 py-14 text-center shadow-xl">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-1/4 w-64 h-64 bg-white rounded-full -translate-y-1/2 blur-2xl"></div>
                <div class="absolute bottom-0 right-1/4 w-64 h-64 bg-white rounded-full translate-y-1/2 blur-2xl"></div>
            </div>
            <div class="relative">
                <p class="text-indigo-200 text-sm font-semibold uppercase tracking-widest mb-3">¿Listo para comprar?</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">
                    Miles de productos te esperan
                </h2>
                <p class="text-indigo-100 mb-8 max-w-md mx-auto text-sm leading-relaxed">
                    Compra con confianza: pagos seguros con Wompi, envío rápido y soporte disponible siempre.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('catalog') }}"
                       class="inline-flex items-center gap-2 bg-white text-indigo-700 px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-indigo-50 transition shadow-lg">
                        Explorar catálogo
                    </a>
                    @guest
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-2 bg-indigo-500/40 text-white border border-white/30 px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-indigo-500/60 transition backdrop-blur-sm">
                        Crear cuenta gratis
                    </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</section>

@endsection