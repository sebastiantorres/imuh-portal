@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-page">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-sm mx-4">
        <img src="{{ asset('images/logo-imuh.jpg') }}" alt="Logo" class="mx-auto mb-6 w-48 h-48">

        {{-- Título de la página --}}
        <h2 class="text-3xl font-extrabold text-center mb-6 text-primary">Iniciar Sesión</h2>
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-primary">Email</label>
                <input 
                  type="email" 
                  name="email" 
                  id="email" 
                  value="{{ old('email') }}" 
                  required 
                  autofocus
                  class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md 
                         focus:outline-none focus:ring-2 focus:ring-secondary focus:border-primary" 
                />
                @error('email')
                  <p class="text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-primary">Contraseña</label>
                <input 
                  type="password" 
                  name="password" 
                  id="password" 
                  required
                  class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md 
                         focus:outline-none focus:ring-2 focus:ring-secondary focus:border-primary" 
                />
                @error('password')
                  <p class="text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button 
                  type="submit"
                  class="w-full py-2 bg-primary hover-bg-secondary text-white-important font-semibold rounded-md 
                         focus:outline-none focus:ring-2 focus:ring-primary"
                >
                  Entrar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
