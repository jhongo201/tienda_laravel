<div class="grid lg:grid-cols-3 gap-6">

    {{-- Formulario --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/60">
                <h2 class="font-bold text-gray-800 text-sm">
                    {{ $editingId ? 'Editar categoría' : 'Nueva categoría' }}
                </h2>
            </div>
            <div class="p-5 space-y-4">
                @if(session('success'))
                    <div class="px-3 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nombre</label>
                    <input wire:model.live="name" type="text" placeholder="Ej: Electrónica"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none"/>
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Slug</label>
                    <input wire:model="slug" type="text" placeholder="electronica"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none"/>
                    @error('slug') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-2 pt-1">
                    <button wire:click="save"
                            class="flex-1 bg-indigo-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">
                        {{ $editingId ? 'Actualizar' : 'Crear' }}
                    </button>
                    @if($editingId)
                        <button wire:click="cancelEdit"
                                class="px-4 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition">
                            Cancelar
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Nombre</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider hidden sm:table-cell">Slug</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Productos</th>
                        <th class="px-5 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50/60 transition {{ $editingId === $category->id ? 'bg-indigo-50/50' : '' }}">
                            <td class="px-5 py-4 font-semibold text-gray-800">{{ $category->name }}</td>
                            <td class="px-5 py-4 hidden sm:table-cell font-mono text-xs text-gray-400">{{ $category->slug }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="bg-indigo-50 text-indigo-600 text-xs font-bold px-2 py-0.5 rounded-full">
                                    {{ $category->products_count }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    <button wire:click="edit({{ $category->id }})"
                                            class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold transition">
                                        Editar
                                    </button>
                                    <button wire:click="delete({{ $category->id }})"
                                            wire:confirm="¿Eliminar '{{ $category->name }}'?"
                                            class="text-xs text-red-500 hover:text-red-700 font-semibold transition">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-gray-400 text-sm">
                                No hay categorías aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
