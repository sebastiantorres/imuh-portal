@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
  <h1 class="text-3xl font-extrabold mb-6">Panel de Control</h1>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Documentos pendientes -->
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
      <h2 class="text-xl font-semibold mb-2">Documentos pendientes</h2>
      <p class="text-4xl font-bold text-indigo-600">{{ $pendingCount }}</p>
      <a href="{{ route('portal.documents') }}" class="mt-2 inline-block text-sm text-indigo-500 hover:underline">Ver documentos</a>
    </div>

    <!-- Cuota vigente -->
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
      <h2 class="text-xl font-semibold mb-2">Cuota vigente</h2>
      @if ($currentInstallment)
        <p class="text-sm">Monto: <strong>${{ number_format($currentInstallment['amount'], 2, ',', '.') }}</strong></p>
        <p class="text-sm">Vencimiento: <strong>{{ \Carbon\Carbon::parse($currentInstallment['due_date'])->format('d/m/Y') }}</strong></p>
      @else
        <p class="text-gray-500">No hay información disponible.</p>
      @endif
    </div>

    <!-- Saldo de financiación -->
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
      <h2 class="text-xl font-semibold mb-2">Saldo de financiación</h2>
      @if (!is_null($balance))
        <p class="text-4xl font-bold text-green-600">${{ number_format($balance, 2, ',', '.') }}</p>
      @else
        <p class="text-gray-500">No hay información disponible.</p>
      @endif
    </div>
  </div>

  <div class="mt-8">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit"
              class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded">
        Cerrar sesión
      </button>
    </form>
  </div>
</div>
@endsection