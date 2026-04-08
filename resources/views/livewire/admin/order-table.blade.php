<div>
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-5">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por referencia..."
                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none"/>
        </div>
        <select wire:model.live="status"
                class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none bg-white">
            <option value="">Todos los estados</option>
            @foreach($statuses as $s)
                <option value="{{ $s->value }}">{{ $s->label() }}</option>
            @endforeach
        </select>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Referencia</th>
                    <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider hidden md:table-cell">Cliente</th>
                    <th class="text-right px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Total</th>
                    <th class="text-center px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Estado</th>
                    <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider hidden sm:table-cell">Fecha</th>
                    <th class="px-5 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @php
                    $badgeColors = [
                        'pending'    => 'bg-amber-100 text-amber-800',
                        'paid'       => 'bg-emerald-100 text-emerald-800',
                        'processing' => 'bg-blue-100 text-blue-800',
                        'shipped'    => 'bg-violet-100 text-violet-800',
                        'delivered'  => 'bg-teal-100 text-teal-800',
                        'cancelled'  => 'bg-red-100 text-red-700',
                        'refunded'   => 'bg-gray-100 text-gray-600',
                        'expired'    => 'bg-gray-100 text-gray-500',
                    ];
                @endphp
                @forelse($orders as $order)
                    @php $sk = $order->status->value ?? 'pending'; @endphp
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-5 py-4">
                            <p class="font-mono text-xs font-bold text-gray-700">{{ $order->reference }}</p>
                        </td>
                        <td class="px-5 py-4 hidden md:table-cell">
                            <p class="font-medium text-gray-700">{{ $order->user?->name ?? '—' }}</p>
                            <p class="text-xs text-gray-400">{{ $order->user?->email }}</p>
                        </td>
                        <td class="px-5 py-4 text-right font-bold text-gray-900">
                            ${{ number_format($order->total, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeColors[$sk] ?? 'bg-gray-100 text-gray-500' }}">
                                {{ $order->status->label() }}
                            </span>
                        </td>
                        <td class="px-5 py-4 hidden sm:table-cell text-gray-500 text-xs">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-5 py-4">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold transition">
                                Ver →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center text-gray-400 text-sm">
                            No se encontraron órdenes.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($orders->hasPages())
            <div class="px-5 py-4 border-t border-gray-50">{{ $orders->links() }}</div>
        @endif
    </div>
</div>
