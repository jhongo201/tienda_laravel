<div>
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-5">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar producto..."
                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none"/>
        </div>
        <label class="flex items-center gap-2 text-sm text-gray-500 cursor-pointer select-none">
            <input wire:model.live="showInactive" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600"/>
            Mostrar inactivos / eliminados
        </label>
        <a href="{{ route('admin.productos.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo producto
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Producto</th>
                    <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider hidden md:table-cell">Categoría</th>
                    <th class="text-right px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Precio</th>
                    <th class="text-right px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider hidden sm:table-cell">Stock</th>
                    <th class="text-center px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Estado</th>
                    <th class="px-5 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50/60 transition {{ $product->trashed() ? 'opacity-50' : '' }}">
                        <td class="px-5 py-4">
                            <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $product->slug }}</p>
                        </td>
                        <td class="px-5 py-4 hidden md:table-cell text-gray-500">{{ $product->category?->name ?? '—' }}</td>
                        <td class="px-5 py-4 text-right font-semibold text-gray-800">
                            ${{ number_format($product->base_price, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-right hidden sm:table-cell">
                            <span class="font-medium {{ $product->stock <= 5 ? 'text-red-500' : 'text-gray-600' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($product->trashed())
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-600">Eliminado</span>
                            @elseif($product->is_active)
                                <button wire:click="toggleActive({{ $product->id }})"
                                        class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition cursor-pointer">
                                    Activo
                                </button>
                            @else
                                <button wire:click="toggleActive({{ $product->id }})"
                                        class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 hover:bg-gray-200 transition cursor-pointer">
                                    Inactivo
                                </button>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-3">
                                @if($product->trashed())
                                    <button wire:click="restore({{ $product->id }})"
                                            class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold transition">Restaurar</button>
                                @else
                                    <a href="{{ route('admin.productos.edit', $product->id) }}"
                                       class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold transition">Editar</a>
                                    <button wire:click="delete({{ $product->id }})"
                                            wire:confirm="¿Eliminar '{{ $product->name }}'?"
                                            class="text-xs text-red-500 hover:text-red-700 font-semibold transition">Eliminar</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center text-gray-400 text-sm">
                            No se encontraron productos.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($products->hasPages())
            <div class="px-5 py-4 border-t border-gray-50">{{ $products->links() }}</div>
        @endif
    </div>
</div>
