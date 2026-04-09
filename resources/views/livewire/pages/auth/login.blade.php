<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-gray-900">Bienvenido de vuelta</h1>
        <p class="mt-1.5 text-sm text-gray-500">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" wire:navigate class="text-indigo-600 font-semibold hover:text-indigo-800 transition">
                Regístrate gratis
            </a>
        </p>
    </div>

    @if (session('status'))
        <div class="mb-5 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login" class="space-y-5">

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">
                Correo electrónico
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input wire:model="form.email" id="email" type="email" name="email"
                       required autofocus autocomplete="username"
                       placeholder="tu@correo.com"
                       class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('form.email') border-red-400 ring-2 ring-red-200 @enderror"/>
            </div>
            @error('form.email')
                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Contraseña --}}
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">
                    Contraseña
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate
                       class="text-xs text-indigo-600 font-semibold hover:text-indigo-800 transition">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input wire:model="form.password" id="password" type="password" name="password"
                       required autocomplete="current-password"
                       placeholder="Tu contraseña"
                       class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('form.password') border-red-400 ring-2 ring-red-200 @enderror"/>
            </div>
            @error('form.password')
                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Recuérdame --}}
        <label for="remember" class="flex items-center gap-2.5 cursor-pointer select-none">
            <div class="relative">
                <input wire:model="form.remember" id="remember" type="checkbox" name="remember" class="sr-only peer">
                <div class="w-9 h-5 bg-gray-200 peer-checked:bg-indigo-600 rounded-full transition"></div>
                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-4 transition-transform"></div>
            </div>
            <span class="text-sm text-gray-600">Mantener sesión iniciada</span>
        </label>

        {{-- Submit --}}
        <button type="submit"
                class="w-full flex items-center justify-center gap-2 bg-indigo-600 text-white py-3.5 px-4 rounded-xl font-bold text-sm hover:bg-indigo-700 active:bg-indigo-800 transition shadow-sm shadow-indigo-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Iniciar sesión
        </button>

    </form>

    {{-- Trust --}}
    <div class="mt-6 flex items-center justify-center gap-1.5 text-xs text-gray-400">
        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
        Conexión segura y encriptada
    </div>
</div>
