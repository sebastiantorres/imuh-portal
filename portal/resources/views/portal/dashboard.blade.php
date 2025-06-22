@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
  {{-- Título --}}
  <h1 class="text-3xl font-extrabold mb-6 text-primary">Panel de Control</h1>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
    <h2 class="text-xl font-semibold mb-2 text-primary">Bienvenido!</h2>
    <div class="space-y-3 text-sm text-gray-800">

      <p>
        <span class="font-medium">Nombre:</span>
        {{ session('portal_user')['first_name'] ?? 'Usuario no identificado' }}
      </p>

      <p>
        <span class="font-medium">Apellido:</span>
        {{ session('portal_user')['last_name'] }}
      </p>
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
      <form method="POST" action="{{ route('logout') }}">
  @csrf
  <button
    type="submit"
    class="px-4 py-2 bg-primary hover-bg-secondary text-white-important font-semibold rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
  >
    Cerrar sesión
  </button>
</form>
    </div>
  </div>
    {{-- Documentos pendientes --}}
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
      <h2 class="text-xl font-semibold mb-2 text-primary">Documentos pendientes</h2>
      <p class="text-4xl font-bold text-primary">{{ $pendingCount }}</p>
      <a href="{{ route('portal.documents') }}"
         class="mt-2 inline-block text-sm text-secondary hover:underline">
        Ver documentos
      </a>
    </div>

    {{-- Cuota vigente --}}
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
      <h2 class="text-xl font-semibold mb-2 text-primary">Cuota vigente</h2>
      @if ($currentInstallment)
        <p class="text-sm">Monto: <strong>${{ number_format($currentInstallment['amount'], 2, ',', '.') }}</strong></p>
        <p class="text-sm">Vencimiento: <strong>{{ \Carbon\Carbon::parse($currentInstallment['due_date'])->format('d/m/Y') }}</strong></p>
        <p class="text-sm">Estado:
          <strong>
            {{ $currentInstallment['status'] == 'paid'
                 ? 'Pagada'
                 : ($currentInstallment['status'] == 'pending'
                    ? 'Pendiente'
                    : ucfirst($currentInstallment['status'])) }}
          </strong>
        </p>
        <p class="text-sm">Número de cuota:
          <strong>{{ $currentInstallment['number'] }} de {{ $funding['number_of_installments'] }}</strong>
        </p>
        @if ($currentInstallment['status'] === 'pending')
          <div class="mt-4">
            <a href="{{ route('portal.installment.voucher.form') }}"
              class="inline-block bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark transition">
              Subir comprobante
            </a>
          </div>
        @endif

       
      @else
        <p class="text-gray-500">No hay información disponible.</p>
      @endif
    </div>

    {{-- Saldo de financiación --}}
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
      <h2 class="text-xl font-semibold mb-2 text-primary">Saldo de financiación</h2>
      @if (!is_null($balance))
        <p class="text-3xl font-bold text-secondary">${{ number_format($balance, 2, ',', '.') }}</p>
      @else
        <p class="text-gray-500">No hay información disponible.</p>
      @endif

      {{-- Mensaje informativo --}}
      <div class="mt-4 p-4 bg-gray-100 rounded border border-gray-200 text-sm text-gray-700">
        <p class="mb-2">
          La información presentada es de carácter <strong>informativo</strong>.
          Para solicitar el saldo exacto necesario para cancelar la totalidad del lote,
          deberá presentarse <strong>personalmente</strong> en nuestras oficinas, ubicadas en
          <strong>calle Planas 569</strong>, en el horario de <strong>08:30 a 14:00 hs</strong>.
        </p>
        <p class="mb-2">
          La solicitud debe realizarse mediante una nota escrita, la cual deberá ser entregada
          en <strong>mesa de entrada del IMUH</strong>.
        </p>
        <p>
          <a href="{{ asset('documents/modelo_nota.docx') }}"
             class="text-secondary hover:underline"
             download>
            Descargar modelo de nota de solicitud
          </a>
        </p>
      </div>
    </div>
  </div>
</div>
@endsection
@if (request()->routeIs('portal.installment.voucher.form') && $currentInstallment)
    @include('portal.vouchers.form', ['installment' => $currentInstallment])
@endif
