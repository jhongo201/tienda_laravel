<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-2xl font-bold text-gray-900 mb-8">Finalizar compra</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- FORMULARIO --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Datos de envío --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="font-bold text-gray-900 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Datos de envío
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Nombre completo --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo *</label>
                        <input wire:model="name"
                               type="text"
                               placeholder="Tu nombre completo"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-400 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Ciudad --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad *</label>
                        <input wire:model="city"
                               type="text"
                               placeholder="Ciudad"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('city') border-red-400 @enderror">
                        @error('city')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Código postal --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Código postal</label>
                        <input wire:model="zip"
                               type="text"
                               placeholder="Opcional"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    {{-- Dirección --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dirección completa *</label>
                        <input wire:model="address"
                               type="text"
                               placeholder="Calle, número, barrio..."
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('address') border-red-400 @enderror">
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Teléfono --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono de contacto *</label>
                        <input wire:model="phone"
                               type="tel"
                               placeholder="Ej: 3001234567"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('phone') border-red-400 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Cupón de descuento --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Cupón de descuento
                </h2>

                @if($couponValid)
                    <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-xl px-4 py-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-sm font-semibold text-green-700">{{ strtoupper($couponCode) }}</span>
                            <span class="text-sm text-green-600">— {{ $couponMessage }}</span>
                        </div>
                        <button wire:click="removeCoupon"
                                class="text-green-400 hover:text-red-500 transition ml-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @else
                    <div class="flex gap-2">
                        <input wire:model="couponCode"
                               type="text"
                               placeholder="CÓDIGO DE CUPÓN"
                               class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-sm uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               wire:keydown.enter="applyCoupon">
                        <button wire:click="applyCoupon"
                                wire:loading.attr="disabled"
                                class="bg-gray-900 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-700 transition disabled:opacity-50 whitespace-nowrap">
                            <span wire:loading.remove wire:target="applyCoupon">Aplicar</span>
                            <span wire:loading wire:target="applyCoupon">...</span>
                        </button>
                    </div>
                    @if($couponMessage && !$couponValid)
                        <p class="text-red-500 text-xs mt-2">{{ $couponMessage }}</p>
                    @endif
                @endif
            </div>

        </div>

        {{-- RESUMEN --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-24 space-y-4">

                <h2 class="font-bold text-gray-900 text-lg">Resumen del pedido</h2>

                {{-- Items --}}
                <ul class="space-y-3 max-h-56 overflow-y-auto pr-1">
                    @foreach($cart->items as $item)
                        <li class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg flex-shrink-0 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-800 line-clamp-1">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-400">× {{ $item->qty }}</p>
                            </div>
                            <span class="text-xs font-bold text-gray-900 whitespace-nowrap">
                                ${{ number_format($item->unit_price * $item->qty, 0, ',', '.') }}
                            </span>
                        </li>
                    @endforeach
                </ul>

                <hr class="border-gray-100">

                {{-- Totales --}}
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($discountAmount > 0)
                        <div class="flex justify-between text-green-600 font-medium">
                            <span>Descuento cupón</span>
                            <span>-${{ number_format($discountAmount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span>Envío</span>
                        <span>${{ number_format($shipping, 0, ',', '.') }}</span>
                    </div>
                </div>

                <hr class="border-gray-100">

                <div class="flex justify-between font-bold text-gray-900 text-base">
                    <span>Total</span>
                    <span>${{ number_format($total, 0, ',', '.') }} COP</span>
                </div>

                {{-- CTA --}}
                <button wire:click="submit"
                        wire:loading.attr="disabled"
                        class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-60 flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="submit">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Pagar con Wompi
                    </span>
                    <span wire:loading wire:target="submit" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                        Procesando...
                    </span>
                </button>

                <div class="flex items-center justify-center gap-2 text-xs text-gray-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Pago seguro · Encriptado SSL
                </div>

                <a href="{{ route('cart.index') }}"
                   class="block text-center text-sm text-gray-400 hover:text-indigo-600 transition">
                    ← Volver al carrito
                </a>
            </div>
        </div>

    </div>
</div>
