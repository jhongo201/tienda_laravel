@extends('layouts.app')

@section('title', 'Mi Dashboard')

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
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Bienvenida --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500 mb-1">Bienvenido de nuevo</p>
            <h1 class="text-3xl font-extrabold text-gray-900 leading-tight">
                {{ $user->name }}
            </h1>
            <p class="text-sm text-gray-400 mt-1">{{ $user->email }}</p>
        </div>
        <a href="{{ route('catalog') }}"
           class="self-start sm:self-auto inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 active:scale-95 transition-all shadow-sm whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            Ir a la tienda
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Pedidos totales</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-gray-900 leading-none">
                ${{ number_format($stats['spent'], 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Total gastado <span class="text-gray-300">COP</span></p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ $stats['pending'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Pendientes de pago</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-teal-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ $stats['delivered'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Entregas completadas</p>
        </div>

    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        {{-- Pedidos recientes --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
                    <h2 class="font-bold text-gray-800">Pedidos recientes</h2>
                    <a href="{{ route('orders.index') }}"
                       class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
                        Ver todos →
                    </a>
                </div>

                @if($recentOrders->isEmpty())
                    <div class="py-14 text-center">
                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <p class="text-sm text-gray-400">Aún no tienes pedidos</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($recentOrders as $order)
                            @php $sk = $order->status->value ?? 'pending'; @endphp
                            <a href="{{ route('orders.show', $order->reference) }}"
                               class="group flex items-center gap-4 px-6 py-4 hover:bg-gray-50/70 transition border-l-4 {{ $statusBorder[$sk] ?? 'border-l-gray-100' }}">
                                <div class="flex-1 min-w-0">
                                    <p class="font-mono text-xs font-bold text-gray-700 truncate">{{ $order->reference }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $order->created_at->translatedFormat('d M Y') }}
                                        @if(isset($order->items_count))
                                            · {{ $order->items_count }} {{ $order->items_count === 1 ? 'producto' : 'productos' }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex items-center gap-3 flex-shrink-0">
                                    <span class="hidden sm:inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusBadge[$sk] ?? 'bg-gray-100 text-gray-500' }}">
                                        {{ $statusLabels[$sk] ?? ucfirst($sk) }}
                                    </span>
                                    <span class="font-extrabold text-sm text-gray-900">
                                        ${{ number_format($order->total, 0, ',', '.') }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-300 group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Panel lateral --}}
        <div class="space-y-4">

            {{-- Acciones rápidas --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50">
                    <h2 class="font-bold text-gray-800">Acciones rápidas</h2>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('catalog') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-indigo-50 text-gray-600 hover:text-indigo-700 transition group">
                        <div class="w-8 h-8 bg-indigo-50 group-hover:bg-indigo-100 rounded-lg flex items-center justify-center transition flex-shrink-0">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold">Ver catálogo</span>
                    </a>
                    <a href="{{ route('orders.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-indigo-50 text-gray-600 hover:text-indigo-700 transition group">
                        <div class="w-8 h-8 bg-indigo-50 group-hover:bg-indigo-100 rounded-lg flex items-center justify-center transition flex-shrink-0">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold">Mis pedidos</span>
                    </a>
                    <a href="{{ route('cart.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-indigo-50 text-gray-600 hover:text-indigo-700 transition group">
                        <div class="w-8 h-8 bg-indigo-50 group-hover:bg-indigo-100 rounded-lg flex items-center justify-center transition flex-shrink-0">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold">Mi carrito</span>
                    </a>
                </div>
            </div>

            {{-- Info de cuenta --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h2 class="font-bold text-gray-800 mb-4">Mi cuenta</h2>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-lg font-extrabold text-indigo-600">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                    </div>
                </div>
                <p class="text-xs text-gray-400">
                    Cliente desde {{ $user->created_at->translatedFormat('F Y') }}
                </p>
            </div>

        </div>
    </div>

</div>
</div>
@endsection
