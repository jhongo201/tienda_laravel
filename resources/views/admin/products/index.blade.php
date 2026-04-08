@extends('layouts.app')

@section('title', 'Productos — Admin')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-indigo-600 transition text-sm">Admin</a>
        <span class="text-gray-300">/</span>
        <h1 class="text-xl font-extrabold text-gray-900">Productos</h1>
    </div>

    @livewire('admin.product-table')

</div>
</div>
@endsection
