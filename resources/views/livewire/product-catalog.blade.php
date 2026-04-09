<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- SIDEBAR — Filtros --}}
        <aside class="w-full lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-24 space-y-6">

                {{-- Búsqueda --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Buscar</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                        </svg>
                        <input wire:model.live.debounce.400ms="search"
                               type="text"
                               placeholder="Nombre del producto..."
                               class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                {{-- Ordenar --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Ordenar por</label>
                    <select wire:model.live="orderBy"
                            class="w-full border border-gray-200 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="created_at">Más recientes</option>
                        <option value="base_price">Precio: menor a mayor</option>
                        <option value="name">Nombre A–Z</option>
                    </select>
                </div>

                {{-- Rango de precio --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Precio (COP)</label>
                    <div class="flex gap-2">
                        <input wire:model.live.debounce.600ms="minPrice"
                               type="number"
                               min="0"
                               placeholder="Mín"
                               class="w-full border border-gray-200 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <input wire:model.live.debounce.600ms="maxPrice"
                               type="number"
                               min="0"
                               placeholder="Máx"
                               class="w-full border border-gray-200 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                {{-- Categorías --}}
                @if($categories->isNotEmpty())
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Categorías</label>
                        <ul class="space-y-1">
                            <li>
                                <button wire:click="$set('category', '')"
                                        class="w-full text-left px-3 py-2 rounded-lg text-sm transition {{ !$category ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                                    Todos
                                </button>
                            </li>
                            @foreach($categories as $cat)
                                <li>
                                    <button wire:click="$set('category', '{{ $cat->slug }}')"
                                            class="w-full text-left px-3 py-2 rounded-lg text-sm transition {{ $category === $cat->slug ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                                        {{ $cat->name }}
                                        <span class="text-gray-400 text-xs ml-1">({{ $cat->products_count }})</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Limpiar filtros --}}
                @if($search || $category || $minPrice || $maxPrice)
                    <button wire:click="$set('search', ''); $set('category', ''); $set('minPrice', null); $set('maxPrice', null)"
                            class="w-full text-sm text-red-500 hover:text-red-700 transition text-center py-1">
                        × Limpiar filtros
                    </button>
                @endif

            </div>
        </aside>

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="flex-1 min-w-0">

            {{-- Header resultado --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ $category ? ucfirst($category) : 'Todos los productos' }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        <span wire:loading.remove>{{ $products->total() }} productos encontrados</span>
                        <span wire:loading class="text-indigo-400">Buscando...</span>
                    </p>
                </div>
            </div>

            {{-- Grid --}}
            <div wire:loading.class="opacity-60 pointer-events-none" class="transition-opacity duration-150">
                @if($products->isEmpty())
                    <div class="text-center py-20 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-lg font-medium">No se encontraron productos</p>
                        <button wire:click="$set('search', ''); $set('category', ''); $set('minPrice', null); $set('maxPrice', null)"
                                class="text-indigo-600 text-sm mt-2 inline-block hover:underline">
                            Ver todos los productos
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <a href="{{ route('product.show', $product->slug) }}"
                               class="group bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg hover:border-indigo-200 transition-all duration-200">

                                <div class="aspect-square bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-indigo-200 group-hover:text-indigo-300 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>

                                <div class="p-4">
                                    <span class="text-xs text-indigo-500 font-medium uppercase tracking-wide">
                                        {{ $product->category->name ?? '' }}
                                    </span>
                                    <h3 class="font-semibold text-gray-900 mt-1 text-sm leading-tight line-clamp-2 group-hover:text-indigo-600 transition">
                                        {{ $product->name }}
                                    </h3>
                                    <div class="flex items-center justify-between mt-3">
                                        <span class="text-lg font-bold text-gray-900">
                                            ${{ number_format($product->base_price, 0, ',', '.') }}
                                        </span>
                                        @if($product->stock > 0)
                                            <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full">En stock</span>
                                        @else
                                            <span class="text-xs text-red-500 bg-red-50 px-2 py-1 rounded-full">Agotado</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-10">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
