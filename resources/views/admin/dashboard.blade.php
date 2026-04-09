@extends('layouts.app')

@section('title', 'Dashboard — Admin')

@php
    use App\Domains\Order\Models\Order;
    use App\Domains\Order\Enums\OrderStatus;
    use App\Domains\Product\Models\Product;
    use App\Domains\Product\Models\Category;
    use Illuminate\Support\Facades\DB;

    $totalOrders    = Order::count();
    $paidOrders     = Order::where('status', OrderStatus::Paid)->count();
    $pendingOrders  = Order::where('status', OrderStatus::Pending)->count();
    $revenue        = Order::whereIn('status', ['paid','processing','shipped','delivered'])->sum('total');
    $totalProducts  = Product::where('is_active', true)->count();
    $lowStock       = Product::where('is_active', true)->where('stock', '<=', 5)->count();

    $latestOrders = Order::with('user')->latest()->take(6)->get();

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

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500 mb-1">Panel de control</p>
            <h1 class="text-2xl font-extrabold text-gray-900">Dashboard</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.productos.create') }}"
               class="inline-flex items-center gap-1.5 bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo producto
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ $totalOrders }}</p>
            <p class="text-xs text-gray-400 mt-1">Pedidos totales</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-extrabold text-gray-900 leading-none">${{ number_format($revenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Ingresos <span class="text-gray-300">COP</span></p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ $pendingOrders }}</p>
            <p class="text-xs text-gray-400 mt-1">Pendientes de pago</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="w-9 h-9 bg-teal-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ $paidOrders }}</p>
            <p class="text-xs text-gray-400 mt-1">Pedidos pagados</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
            </div>
            <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ $totalProducts }}</p>
            <p class="text-xs text-gray-400 mt-1">Productos activos</p>
        </div>

        <div class="bg-white rounded-2xl border {{ $lowStock > 0 ? 'border-red-100' : 'border-gray-100' }} shadow-sm p-5">
            <div class="w-9 h-9 {{ $lowStock > 0 ? 'bg-red-50' : 'bg-gray-50' }} rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 {{ $lowStock > 0 ? 'text-red-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <p class="text-2xl font-extrabold {{ $lowStock > 0 ? 'text-red-600' : 'text-gray-900' }} leading-none">{{ $lowStock }}</p>
            <p class="text-xs text-gray-400 mt-1">Stock bajo (≤5 uds.)</p>
        </div>

    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        {{-- Órdenes recientes --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
                    <h2 class="font-bold text-gray-800">Órdenes recientes</h2>
                    <a href="{{ route('admin.orders.index') }}"
                       class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">Ver todas →</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($latestOrders as $order)
                        @php $sk = $order->status->value ?? 'pending'; @endphp
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="group flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50/70 transition">
                            <div class="flex-1 min-w-0">
                                <p class="font-mono text-xs font-bold text-gray-700 truncate">{{ $order->reference }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $order->user?->name ?? 'Sin usuario' }}
                                    · {{ $order->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                <span class="hidden sm:inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeColors[$sk] ?? 'bg-gray-100 text-gray-500' }}">
                                    {{ $order->status->label() }}
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
            </div>
        </div>

        {{-- Navegación admin --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50">
                    <h2 class="font-bold text-gray-800">Gestión</h2>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('admin.productos.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-indigo-50 text-gray-600 hover:text-indigo-700 transition group">
                        <div class="w-8 h-8 bg-indigo-50 group-hover:bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0 transition">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">Productos</p>
                            <p class="text-xs text-gray-400">{{ $totalProducts }} activos</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.categorias.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-indigo-50 text-gray-600 hover:text-indigo-700 transition group">
                        <div class="w-8 h-8 bg-indigo-50 group-hover:bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0 transition">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold">Categorías</p>
                    </a>
                    <a href="{{ route('admin.orders.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-indigo-50 text-gray-600 hover:text-indigo-700 transition group">
                        <div class="w-8 h-8 bg-indigo-50 group-hover:bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0 transition">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">Órdenes</p>
                            <p class="text-xs text-gray-400">{{ $pendingOrders }} pendientes</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
@endsection