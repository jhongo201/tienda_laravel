@extends('layouts.app')

@section('title', 'Mis Pedidos')

@php
    $statusBadge = [
        'pending'    => 'bg-amber-100 text-amber-800',
        'paid'       => 'bg-emerald-100 text-emerald-800',
        'processing' => 'bg-blue-100 text-blue-800',
        'shipped'    => 'bg-violet-100 text-violet-800',
        'delivered'  => 'bg-teal-100 text-teal-800',
        'cancelled'  => 'bg-red-100 text-red-700',
        'refunded'   => 'bg-gray-100 text-gray-600',
        'expired'    => 'bg-gray-100 text-gray-500',
    ];
    $statusBorder = [
        'pending'    => 'border-l-amber-400',
        'paid'       => 'border-l-emerald-500',
        'processing' => 'border-l-blue-500',
        'shipped'    => 'border-l-violet-500',
        'delivered'  => 'border-l-teal-500',
        'cancelled'  => 'border-l-red-400',
        'refunded'   => 'border-l-gray-300',
        'expired'    => 'border-l-gray-200',
    ];
    $statusLabels = [
        'pending'    => 'Pendiente',
        'paid'       => 'Pagado',
        'processing' => 'En proceso',
        'shipped'    => 'Enviado',
        'delivered'  => 'Entregado',
        'cancelled'  => 'Cancelado',
        'refunded'   => 'Reembolsado',
        'expired'    => 'Expirado',
    ];
@endphp

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500 mb-1">Mi cuenta</p>
            <h1 class="text-3xl font-extrabold text-gray-900 leading-tight">Mis Pedidos</h1>
            <p class="text-sm text-gray-400 mt-1">{{ $orders->total() }} {{ $orders->total() === 1 ? 'pedido' : 'pedidos' }} en total</p>
        </div>
        <a href="{{ route('catalog') }}"
           class="self-start sm:self-auto inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 active:scale-95 transition-all shadow-sm whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            Seguir comprando
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl border-2 border-dashed border-gray-200 py-24 text-center">
            <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Sin pedidos aún</h3>
            <p class="text-sm text-gray-400 mb-8 max-w-xs mx-auto">Cuando realices tu primera compra aparecerá aquí con todos los detalles.</p>
            <a href="{{ route('catalog') }}"
               class="inline-flex items-center gap-2 bg-indigo-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-indigo-700 transition text-sm shadow-sm">
                Explorar productos
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                @php $sk = $order->status->value ?? 'pending'; @endphp
                <a href="{{ route('orders.show', $order->reference) }}"
                   class="group flex flex-col sm:flex-row sm:items-center bg-white rounded-2xl shadow-sm border border-gray-100 border-l-4 {{ $statusBorder[$sk] ?? 'border-l-gray-200' }} hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 overflow-hidden">

                    {{-- Contenido principal --}}
                    <div class="flex-1 px-6 py-5">
                        <div class="flex flex-wrap items-center gap-3 mb-2">
                            <span class="font-mono text-sm font-bold text-gray-800 truncate max-w-xs">
                                {{ $order->reference }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusBadge[$sk] ?? 'bg-gray-100 text-gray-500' }}">
                                {{ $statusLabels[$sk] ?? ucfirst($sk) }}
                            </span>
                        </div>
                        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $order->created_at->translatedFormat('d M Y') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $order->created_at->format('H:i') }}
                            </span>
                            @if(isset($order->items_count))
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                </svg>
                                {{ $order->items_count }} {{ $order->items_count === 1 ? 'producto' : 'productos' }}
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- Total + flecha --}}
                    <div class="flex items-center justify-between sm:justify-end gap-4 px-6 py-4 sm:py-5 border-t border-gray-50 sm:border-t-0 sm:border-l sm:border-l-gray-50 bg-gray-50/50 sm:bg-transparent sm:min-w-[160px]">
                        <div>
                            <p class="text-xl font-extrabold text-gray-900 leading-none">
                                ${{ number_format($order->total, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">COP</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-300 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>

        @if($orders->hasPages())
            <div class="mt-8">{{ $orders->links() }}</div>
        @endif
    @endif

</div>
</div>
@endsection
