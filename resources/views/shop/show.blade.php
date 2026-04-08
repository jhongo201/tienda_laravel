@extends('layouts.app')

@section('title', $product->name)

@section('content')
@if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    </div>
@endif
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Inicio</a>
        <span>/</span>
        <a href="{{ route('catalog') }}" class="hover:text-indigo-600 transition">Productos</a>
        <span>/</span>
        @if($product->category)
            <a href="{{ route('catalog', ['category' => $product->category->slug]) }}"
               class="hover:text-indigo-600 transition">{{ $product->category->name }}</a>
            <span>/</span>
        @endif
        <span class="text-gray-900 font-medium">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

        <!-- IMAGEN -->
        <div class="aspect-square bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl flex items-center justify-center">
            <svg class="w-32 h-32 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>

        <!-- INFO -->
        <div class="flex flex-col">

            <!-- Categoría -->
            @if($product->category)
                <a href="{{ route('catalog', ['category' => $product->category->slug]) }}"
                   class="text-sm font-medium text-indigo-600 uppercase tracking-wide hover:underline mb-2">
                    {{ $product->category->name }}
                </a>
            @endif

            <!-- Nombre -->
            <h1 class="text-3xl font-bold text-gray-900 leading-tight">
                {{ $product->name }}
            </h1>

            <!-- Precio -->
            <div class="mt-4">
                <span class="text-4xl font-bold text-gray-900">
                    ${{ number_format($product->base_price, 0, ',', '.') }}
                </span>
                <span class="text-sm text-gray-400 ml-2">COP</span>
            </div>

            <!-- Stock -->
            <div class="mt-3">
                @if($product->stock > 0)
                    <span class="inline-flex items-center gap-1.5 text-sm text-green-700 bg-green-50 px-3 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                        {{ $product->stock }} unidades disponibles
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 text-sm text-red-600 bg-red-50 px-3 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                        Agotado
                    </span>
                @endif
            </div>

            <hr class="my-6 border-gray-100">

            <!-- Descripción -->
            @if($product->description)
                <div class="text-gray-600 text-sm leading-relaxed">
                    {{ $product->description }}
                </div>
                <hr class="my-6 border-gray-100">
            @endif

            <!-- Variantes -->
            @if($product->variants->isNotEmpty())
                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-700 mb-3">Variantes disponibles</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->variants->where('is_active', true) as $variant)
                            <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm hover:border-indigo-500 hover:text-indigo-600 transition">
                                @foreach($variant->attributes as $key => $val)
                                    {{ ucfirst($key) }}: {{ $val }}
                                @endforeach
                                @if($variant->price_modifier > 0)
                                    <span class="text-gray-400 ml-1">(+${{ number_format($variant->price_modifier, 0, ',', '.') }})</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Cantidad + Agregar al carrito -->
            @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="flex items-center gap-4 mb-4">
                        <label class="text-sm font-medium text-gray-700">Cantidad</label>
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                            <button type="button"
                                    onclick="changeQty(-1)"
                                    class="px-3 py-2 text-gray-500 hover:bg-gray-50 transition text-lg leading-none">−</button>
                            <input type="number" name="qty" id="qty" value="1" min="1" max="{{ $product->stock }}"
                                   class="w-14 text-center text-sm border-0 focus:ring-0 py-2">
                            <button type="button"
                                    onclick="changeQty(1)"
                                    class="px-3 py-2 text-gray-500 hover:bg-gray-50 transition text-lg leading-none">+</button>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-indigo-600 text-white py-3 px-6 rounded-xl font-semibold hover:bg-indigo-700 active:scale-95 transition-all duration-150 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-10H5.4M7 13L5.4 5M7 13l-1.5 6h11"/>
                        </svg>
                        Agregar al carrito
                    </button>
                </form>
            @else
                <button disabled
                        class="w-full bg-gray-200 text-gray-400 py-3 px-6 rounded-xl font-semibold cursor-not-allowed">
                    Producto agotado
                </button>
            @endif

            <!-- Info extra -->
            <div class="mt-6 grid grid-cols-2 gap-3">
                <div class="flex items-center gap-2 text-xs text-gray-500 bg-gray-50 rounded-lg px-3 py-2">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12"/>
                    </svg>
                    Envío a todo Colombia
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500 bg-gray-50 rounded-lg px-3 py-2">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Pago seguro con Wompi
                </div>
            </div>

        </div>
    </div>

</div>

<script>
function changeQty(delta) {
    const input = document.getElementById('qty');
    const max   = parseInt(input.max);
    const newVal = Math.min(Math.max(1, parseInt(input.value) + delta), max);
    input.value = newVal;
}
</script>
@endsection