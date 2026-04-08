@extends('layouts.app')

@section('title', 'Estado del pago')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">

    @if($status === 'APPROVED')
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">¡Pago exitoso!</h1>
        <p class="text-gray-500 text-sm mb-2">Tu pedido ha sido confirmado.</p>
        @if($reference)
            <p class="text-xs text-gray-400 mb-8">
                Referencia: <span class="font-mono font-semibold text-gray-600">{{ $reference }}</span>
            </p>
        @endif
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('orders.index') }}"
               class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-indigo-700 transition text-sm">
                Ver mis pedidos
            </a>
            <a href="{{ route('catalog') }}"
               class="border border-gray-300 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition text-sm">
                Seguir comprando
            </a>
        </div>

    @elseif(in_array($status, ['DECLINED', 'VOIDED', 'ERROR']))
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Pago rechazado</h1>
        <p class="text-gray-500 text-sm mb-8">No se pudo procesar tu pago. Puedes intentarlo nuevamente.</p>
        <a href="{{ route('cart.index') }}"
           class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-indigo-700 transition text-sm">
            Volver al carrito
        </a>

    @elseif($status === 'PENDING')
        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Pago en proceso</h1>
        <p class="text-gray-500 text-sm mb-8">Tu pago está siendo procesado. Te notificaremos por correo cuando se confirme.</p>
        <a href="{{ route('orders.index') }}"
           class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-indigo-700 transition text-sm">
            Ver mis pedidos
        </a>

    @else
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Estado desconocido</h1>
        <p class="text-gray-500 text-sm mb-8">
            No pudimos confirmar el estado de tu pago. Revisa tu correo o
            <a href="{{ route('orders.index') }}" class="text-indigo-600 hover:underline">consulta tus pedidos</a>.
        </p>
    @endif

</div>
@endsection
