<div>
    @if(session('success'))
        <div class="mb-5 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">

        {{-- Columna principal --}}
        <div class="lg:col-span-2 space-y-5">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/60">
                    <h2 class="font-bold text-gray-800 text-sm">Información del producto</h2>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nombre <span class="text-red-400">*</span></label>
                        <input wire:model.live="name" type="text" placeholder="Nombre del producto"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none"/>
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Slug</label>
                        <input wire:model="slug" type="text" placeholder="nombre-del-producto"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none"/>
                        @error('slug') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Descripción</label>
                        <textarea wire:model="description" rows="4" placeholder="Descripción del producto..."
                                  class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none resize-none"></textarea>
                        @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

        </div>

        {{-- Columna lateral --}}
        <div class="space-y-5">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/60">
                    <h2 class="font-bold text-gray-800 text-sm">Precio y stock</h2>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Precio base (COP) <span class="text-red-400">*</span></label>
                        <input wire:model="base_price" type="number" min="0" placeholder="0"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none"/>
                        @error('base_price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Stock <span class="text-red-400">*</span></label>
                        <input wire:model="stock" type="number" min="0" placeholder="0"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none"/>
                        @error('stock') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/60">
                    <h2 class="font-bold text-gray-800 text-sm">Organización</h2>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Categoría</label>
                        <select wire:model="category_id"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none bg-white">
                            <option value="">Sin categoría</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input wire:model="is_active" type="checkbox"
                               class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"/>
                        <span class="text-sm font-medium text-gray-700">Producto activo</span>
                    </label>
                </div>
            </div>

            <button wire:click="save"
                    class="w-full bg-indigo-600 text-white px-4 py-3 rounded-xl text-sm font-bold hover:bg-indigo-700 transition shadow-sm">
                {{ $productId ? 'Actualizar producto' : 'Crear producto' }}
            </button>

            @if(!$productId)
                <a href="{{ route('admin.productos.index') }}"
                   class="block w-full text-center border border-gray-200 text-gray-600 px-4 py-3 rounded-xl text-sm font-semibold hover:bg-gray-50 transition">
                    Cancelar
                </a>
            @else
                <a href="{{ route('admin.productos.index') }}"
                   class="block w-full text-center border border-gray-200 text-gray-600 px-4 py-3 rounded-xl text-sm font-semibold hover:bg-gray-50 transition">
                    ← Volver al listado
                </a>
            @endif

        </div>
    </div>
</div>
