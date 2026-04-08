@extends('layouts.app')

@section('title', 'Orden ' . $order->reference . ' — Admin')

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
    $sk = $order->status->value ?? 'pending';
@endphp

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-indigo-600 transition text-sm">Admin</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('admin.orders.index') }}" class="text-gray-400 hover:text-indigo-600 transition text-sm">Órdenes</a>
        <span class="text-gray-300">/</span>
        <span class="text-gray-600 font-mono text-sm">{{ $order->reference }}</span>
    </div>

    @if(session('success'))
        <div class="mb-5 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Hero --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 border-l-4 {{ $statusBorder[$sk] }} overflow-hidden mb-5">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Referencia</p>
                    <p class="font-mono text-lg font-extrabold text-gray-900">{{ $order->reference }}</p>
                    <div class="flex flex-wrap items-center gap-4 mt-2 text-xs text-gray-400">
                        <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        @if($order->user)
                            <span class="font-medium text-gray-600">{{ $order->user->name }} — {{ $order->user->email }}</span>
                        @endif
                    </div>
                </div>
                <span class="self-start inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold {{ $statusBadge[$sk] ?? 'bg-gray-100 text-gray-500' }}">
                    {{ $order->status->label() }}
                </span>
            </div>

            <div class="mt-6 pt-5 border-t border-gray-50">
                <p class="text-xs text-gray-400 mb-1">Total</p>
                <p class="text-3xl font-extrabold text-gray-900">
                    ${{ number_format($order->total, 0, ',', '.') }}
                    <span class="text-sm font-normal text-gray-400 ml-1">COP</span>
                </p>
            </div>
        </div>
    </div>

    <div class="grid sm:grid-cols-2 gap-4 mb-4">

        {{-- Cambiar estado --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center gap-2.5 px-5 py-3.5 border-b border-gray-50 bg-gray-50/60">
                <div class="w-7 h-7 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700">Cambiar estado</span>
            </div>
            <div class="p-5">
                @php $transitions = $order->status->allowedTransitions(); @endphp
                @if(count($transitions))
                    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="space-y-3">
                        @csrf @method('PATCH')
                        <select name="status"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-300 outline-none bg-white">
                            @foreach($transitions as $t)
                                <option value="{{ $t->value }}">{{ $t->label() }}</option>
                            @endforeach
                        </select>
                        <button type="submit"
                                class="w-full bg-indigo-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">
                            Actualizar estado
                        </button>
                    </form>
                @else
                    <p class="text-sm text-gray-400 text-center py-2">No hay transiciones disponibles desde <strong>{{ $order->status->label() }}</strong>.</p>
                @endif
            </div>
        </div>

        {{-- Resumen financiero --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center gap-2.5 px-5 py-3.5 border-b border-gray-50 bg-gray-50/60">
                <div class="w-7 h-7 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700">Desglose</span>
            </div>
            <div class="px-5 py-4 space-y-2.5 text-sm">
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
                <div class="flex justify-between items-center pt-2.5 border-t-2 border-gray-100">
                    <span class="font-bold text-gray-900">Total</span>
                    <span class="font-extrabold text-gray-900 text-base">${{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Productos --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-5">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Productos</span>
            <span class="bg-indigo-50 text-indigo-600 text-xs font-bold px-2.5 py-0.5 rounded-full">{{ $order->items->count() }}</span>
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
                    <p class="text-sm font-extrabold text-gray-900">${{ number_format($item->unit_price * $item->qty, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400">COP</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Pagos --}}
    @if($order->payments && $order->payments->count())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-5">
        <div class="px-5 py-4 border-b border-gray-50">
            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Pagos registrados</span>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($order->payments as $payment)
            <div class="flex items-center justify-between px-5 py-4 text-sm">
                <div>
                    <p class="font-mono text-xs text-gray-500">{{ $payment->wompi_id ?? '—' }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $payment->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <span class="font-extrabold text-gray-900">${{ number_format($payment->amount, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <a href="{{ route('admin.orders.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-indigo-600 transition">
        ← Volver a órdenes
    </a>

</div>
</div>
@endsection
