@extends('layouts.app')

@section('title', 'Pedido ' . $order->reference)

@php
    $sk = $order->status->value ?? 'pending';
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
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm mb-8">
        <a href="{{ route('orders.index') }}"
           class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-800 font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Mis Pedidos
        </a>
        <span class="text-gray-300">/</span>
        <span class="text-gray-500 font-mono text-xs truncate max-w-[200px]">{{ $order->reference }}</span>
    </nav>

    {{-- Hero card con borde de color --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 border-l-4 {{ $statusBorder[$sk] ?? 'border-l-gray-200' }} overflow-hidden mb-5">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Referencia del pedido</p>
                    <p class="font-mono text-lg font-extrabold text-gray-900 leading-tight">{{ $order->reference }}</p>
                    <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-gray-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $order->created_at->translatedFormat('d \d\e F Y') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $order->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
                <span class="self-start inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold {{ $statusBadge[$sk] ?? 'bg-gray-100 text-gray-500' }}">
                    {{ $statusLabels[$sk] ?? ucfirst($sk) }}
                </span>
            </div>

            {{-- Total destacado --}}
            <div class="mt-6 pt-5 border-t border-gray-50 flex flex-wrap items-end gap-6">
                <div>
                    <p class="text-xs text-gray-400 mb-1">Total pagado</p>
                    <p class="text-3xl font-extrabold text-gray-900 leading-none">
                        ${{ number_format($order->total, 0, ',', '.') }}
                        <span class="text-base font-normal text-gray-400 ml-1">COP</span>
                    </p>
                </div>
                @if($order->discount_amount > 0)
                <div class="pb-0.5">
                    <p class="text-xs text-gray-400 mb-1">Descuento aplicado</p>
                    <p class="text-lg font-bold text-emerald-600">
                        -${{ number_format($order->discount_amount, 0, ',', '.') }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Grid: Dirección + Resumen --}}
    <div class="grid sm:grid-cols-2 gap-4 mb-4">

        @if($order->address_snapshot)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center gap-2.5 px-5 py-3.5 border-b border-gray-50 bg-gray-50/60">
                <div class="w-7 h-7 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700">Dirección de envío</span>
            </div>
            <div class="px-5 py-4 space-y-2 text-sm">
                <p class="font-bold text-gray-900 text-base">{{ $order->address_snapshot['name'] ?? '' }}</p>
                <p class="text-gray-500">{{ $order->address_snapshot['address'] ?? '' }}</p>
                <p class="text-gray-500">
                    {{ $order->address_snapshot['city'] ?? '' }}
                    @if(!empty($order->address_snapshot['zip']))
                        <span class="text-gray-400 font-medium">· CP {{ $order->address_snapshot['zip'] }}</span>
                    @endif
                </p>
                <p class="inline-flex items-center gap-1.5 text-gray-500 pt-1">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    {{ $order->address_snapshot['phone'] ?? '' }}
                </p>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center gap-2.5 px-5 py-3.5 border-b border-gray-50 bg-gray-50/60">
                <div class="w-7 h-7 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700">Resumen de pago</span>
            </div>
            <div class="px-5 py-4 space-y-3 text-sm">
                <div class="flex justify-between text-gray-500">
                    <span>Subtotal</span>
                    <span class="font-medium text-gray-700">${{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between text-emerald-600 font-semibold">
                    <span>Descuento</span>
                    <span>-${{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between text-gray-500">
                    <span>Envío</span>
                    <span class="font-medium text-gray-700">${{ number_format($order->shipping_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t-2 border-gray-100">
                    <span class="font-bold text-gray-900 text-base">Total</span>
                    <span class="font-extrabold text-gray-900 text-lg">${{ number_format($order->total, 0, ',', '.') }}
                        <span class="text-xs font-normal text-gray-400 ml-0.5">COP</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Productos --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
            <h2 class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-400">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
                Productos
            </h2>
            <span class="bg-indigo-50 text-indigo-600 text-xs font-bold px-2.5 py-0.5 rounded-full">
                {{ $order->items->count() }}
            </span>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($order->items as $item)
            <div class="flex items-center gap-4 px-5 py-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0 text-sm font-extrabold text-indigo-500">
                    {{ $item->qty }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $item->name_snapshot }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">${{ number_format($item->unit_price, 0, ',', '.') }} por unidad</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-extrabold text-gray-900">
                        ${{ number_format($item->unit_price * $item->qty, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-400">COP</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Acciones --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('orders.index') }}"
           class="flex-1 text-center bg-white border border-gray-200 text-gray-600 px-5 py-3 rounded-xl text-sm font-semibold hover:bg-gray-50 hover:border-gray-300 transition">
            ← Volver a pedidos
        </a>
        <a href="{{ route('catalog') }}"
           class="flex-1 text-center bg-indigo-600 text-white px-5 py-3 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
            Seguir comprando
        </a>
    </div>

</div>
</div>
@endsection
