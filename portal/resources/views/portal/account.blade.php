@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
  <h1 class="text-3xl font-extrabold mb-6 text-primary">Mi cuenta</h1>

  <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
    <div class="space-y-3 text-sm text-gray-800">
      {{-- Fecha de firma del mutuo: viene en $allocation['signed_date'] --}}
      <p>
        <span class="font-medium">Fecha de firma del mutuo:</span>
        {{ \Carbon\Carbon::parse($allocation['date'])->format('d/m/Y') }}
      </p>

      {{-- Grupo de financiación: asumiendo que viene en $funding_group['name'] --}}
      <p>
        <span class="font-medium">Grupo de financiación:</span>
        {{ $funding_group['name'] ?? 'Sin grupo asignado' }}
      </p>

      <p>
        <span class="font-medium">Anticipo:</span>
        {{ isset($funding_group['advance'])
            ? number_format($funding_group['advance'], 2, ',', '.') . ' %'
            : '0 %' }}
      </p>

      {{-- Cantidad de cuotas: asumiendo que viene en $funding['number_of_installments'] --}}
      <p>
        <span class="font-medium">Cantidad de cuotas:</span>
        {{ $funding_group['total_installments'] ?? 0 }} 
        ({{ $funding_group['number_of_installments_advance'] ?? 0 }} cuotas de anticipo)
        ({{ $funding_group['number_of_installments_normal'] ?? 0 }} cuotas de financiación)
      </p>
    </div>
  </div>
</div>
@endsection
