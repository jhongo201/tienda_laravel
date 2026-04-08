@extends('layouts.app')

@section('title', 'Mi Carrito')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-2xl font-bold text-gray-900 mb-8">Mi Carrito</h1>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($cart->items->isEmpty())
        <div class="text-center py-20">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M3 3h2l.4 2M7 13h10l4-10H5.4M7 13L5.4 5M7 13l-1.5 6h11"/>
            </svg>
            <p class="text-gray-500 text-lg font-medium">Tu carrito está vacío</p>
            <a href="{{ route('catalog') }}"
               class="mt-4 inline-block bg-indigo-600 text-white px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition text-sm font-medium">
                Ver productos
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- ITEMS -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($cart->items as $item)
                <div class="bg-white rounded-2xl border border-gray-200 p-4 flex gap-4">

                    <!-- Imagen -->
                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl flex-shrink-0 flex items-center justify-center">
                        <svg class="w-8 h-8 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/>
                        </svg>
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('product.show', $item->product->slug) }}"
                           class="font-semibold text-gray-900 hover:text-indigo-600 transition text-sm line-clamp-2">
                            {{ $item->product->name }}
                        </a>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $item->product->category->name ?? '' }}
                        </p>
                        <p class="text-indigo-600 font-bold mt-1 text-sm">
                            ${{ number_format($item->unit_price, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Cantidad y eliminar -->
                    <div class="flex flex-col items-end justify-between">
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-300 hover:text-red-400 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>

                        <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex">
                                @csrf @method('PATCH')
                                <button type="submit" name="qty" value="{{ $item->qty - 1 }}"
                                        class="px-2 py-1 text-gray-400 hover:bg-gray-50 transition text-sm">−</button>
                                <span class="px-3 py-1 text-sm font-medium border-x border-gray-200">{{ $item->qty }}</span>
                                <button type="submit" name="qty" value="{{ $item->qty + 1 }}"
                                        class="px-2 py-1 text-gray-400 hover:bg-gray-50 transition text-sm">+</button>
                            </form>
                        </div>

                        <p class="text-sm font-bold text-gray-900">
                            ${{ number_format($item->unit_price * $item->qty, 0, ',', '.') }}
                        </p>
                    </div>

                </div>
                @endforeach
            </div>

            <!-- RESUMEN -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-24">
                    <h2 class="font-bold text-gray-900 text-lg mb-4">Resumen del pedido</h2>

                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <div class="flex justify-between">
                            <span>Subtotal ({{ $count }} items)</span>
                            <span>${{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Envío</span>
                            <span class="text-green-600">Calculado al pagar</span>
                        </div>
                    </div>

                    <hr class="border-gray-100 my-4">

                    <div class="flex justify-between font-bold text-gray-900 text-base mb-6">
                        <span>Total</span>
                        <span>${{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    @auth
                        <a href="{{ route('checkout') }}"
                           class="block w-full bg-indigo-600 text-white text-center py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
                            Proceder al pago
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="block w-full bg-indigo-600 text-white text-center py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
                            Iniciar sesión para pagar
                        </a>
                        <p class="text-xs text-gray-400 text-center mt-2">
                            ¿No tienes cuenta?
                            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Regístrate gratis</a>
                        </p>
                    @endauth

                    <a href="{{ route('catalog') }}"
                       class="block w-full text-center text-sm text-gray-500 hover:text-indigo-600 transition mt-3">
                        ← Seguir comprando
                    </a>
                </div>
            </div>

        </div>
    @endif

</div>
@endsection