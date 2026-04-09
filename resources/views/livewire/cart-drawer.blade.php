<div x-data x-on:keydown.escape.window="$wire.open && $wire.toggle()">

    {{-- Botón del carrito (icono + badge) --}}
    <button wire:click="toggle"
            class="relative text-gray-600 hover:text-indigo-600 transition"
            aria-label="Abrir carrito">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-10H5.4M7 13L5.4 5M7 13l-1.5 6h11"/>
        </svg>
        @if($count > 0)
            <span class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center leading-none">
                {{ $count > 9 ? '9+' : $count }}
            </span>
        @endif
    </button>

    {{-- Overlay --}}
    @if($open)
        <div wire:click="toggle"
             class="fixed inset-0 bg-black/40 z-40 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>
    @endif

    {{-- Panel deslizable --}}
    <div class="fixed top-0 right-0 h-full w-full sm:w-96 bg-white shadow-2xl z-50 flex flex-col transform transition-transform duration-300 ease-in-out {{ $open ? 'translate-x-0' : 'translate-x-full' }}"
         aria-hidden="{{ $open ? 'false' : 'true' }}">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-10H5.4M7 13L5.4 5M7 13l-1.5 6h11"/>
                </svg>
                Mi Carrito
                @if($count > 0)
                    <span class="text-sm font-normal text-gray-400">({{ $count }} {{ $count === 1 ? 'item' : 'items' }})</span>
                @endif
            </h2>
            <button wire:click="toggle" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Items --}}
        <div class="flex-1 overflow-y-auto px-5 py-4">
            @if($cart->items->isEmpty())
                <div class="flex flex-col items-center justify-center h-full text-center py-16">
                    <svg class="w-14 h-14 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 3h2l.4 2M7 13h10l4-10H5.4M7 13L5.4 5M7 13l-1.5 6h11"/>
                    </svg>
                    <p class="text-gray-400 font-medium">Tu carrito está vacío</p>
                    <button wire:click="toggle"
                            onclick="window.location='{{ route('catalog') }}'"
                            class="mt-4 text-sm text-indigo-600 hover:underline">
                        Ver productos →
                    </button>
                </div>
            @else
                <ul class="space-y-4" wire:loading.class="opacity-50">
                    @foreach($cart->items as $item)
                        <li class="flex gap-3 items-start">
                            {{-- Imagen placeholder --}}
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl flex-shrink-0 flex items-center justify-center">
                                <svg class="w-7 h-7 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/>
                                </svg>
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 leading-tight line-clamp-2">
                                    {{ $item->product->name }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    ${{ number_format($item->unit_price, 0, ',', '.') }} c/u
                                </p>

                                {{-- Controles de cantidad --}}
                                <div class="flex items-center gap-2 mt-2">
                                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                        <button wire:click="updateQty({{ $item->id }}, {{ $item->qty - 1 }})"
                                                class="px-2 py-1 text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition text-sm leading-none">
                                            −
                                        </button>
                                        <span class="px-2.5 py-1 text-sm font-medium text-gray-800 border-x border-gray-200 min-w-[2rem] text-center">
                                            {{ $item->qty }}
                                        </span>
                                        <button wire:click="updateQty({{ $item->id }}, {{ $item->qty + 1 }})"
                                                class="px-2 py-1 text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition text-sm leading-none">
                                            +
                                        </button>
                                    </div>
                                    <button wire:click="remove({{ $item->id }})"
                                            wire:confirm="¿Eliminar este producto?"
                                            class="text-gray-300 hover:text-red-400 transition p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Subtotal item --}}
                            <p class="text-sm font-bold text-gray-900 whitespace-nowrap">
                                ${{ number_format($item->unit_price * $item->qty, 0, ',', '.') }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Footer con total y CTA --}}
        @if($cart->items->isNotEmpty())
            <div class="border-t border-gray-100 px-5 py-4 bg-white space-y-3">
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Subtotal</span>
                    <span class="font-semibold text-gray-900">${{ number_format($total, 0, ',', '.') }} COP</span>
                </div>
                <p class="text-xs text-gray-400">El envío se calcula al momento del pago.</p>

                @auth
                    <a href="{{ route('checkout') }}"
                       class="block w-full bg-indigo-600 text-white text-center py-3 rounded-xl font-semibold hover:bg-indigo-700 active:scale-95 transition-all text-sm">
                        Proceder al pago →
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="block w-full bg-indigo-600 text-white text-center py-3 rounded-xl font-semibold hover:bg-indigo-700 transition text-sm">
                        Iniciar sesión para pagar
                    </a>
                @endauth

                <a href="{{ route('cart.index') }}"
                   class="block w-full text-center text-sm text-gray-400 hover:text-indigo-600 transition py-1">
                    Ver carrito completo
                </a>
            </div>
        @endif

    </div>
</div>
