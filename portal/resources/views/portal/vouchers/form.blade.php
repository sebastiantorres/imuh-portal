@extends('layouts.app')

@section('content')
@php
  $canUpload = true;
@endphp

<div class="container mx-auto p-6">
  <h1 class="text-2xl font-bold mb-4 text-primary">Adjuntar comprobante de pago</h1>

  @if (session('success'))
    <div class="mb-4 p-4 border-l-4 border-green-500 bg-green-50 text-green-800 rounded">
      <strong>¡Éxito!</strong> {{ session('success') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="mb-4 p-4 border-l-4 border-red-500 bg-red-50 text-red-800 rounded">
      <strong>Se encontraron errores:</strong>
      <ul class="mt-2 list-disc list-inside text-sm">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="bg-white p-6 rounded-lg shadow">

    {{-- ✅ Información de la cuota --}}
    <div class="mb-6">
      <p><strong>Cuota #:</strong> {{ $installment['number'] }}</p>
      <p><strong>Monto:</strong> ${{ number_format($installment['amount'], 2, ',', '.') }}</p>
      <p><strong>Vencimiento:</strong> {{ \Carbon\Carbon::parse($installment['due_date'])->format('d/m/Y') }}</p>
      <p><strong>Estado:</strong> {{ ucfirst($installment['status']) }}</p>
    </div>

    {{-- 🧾 Comprobantes cargados anteriormente --}}
    @if (!empty($installment['vouchers']))
      @foreach ($installment['vouchers'] as $voucher)
        <div class="mb-6 bg-gray-100 border border-gray-300 p-4 rounded">
          <p class="font-semibold text-gray-800 mb-1">Comprobante enviado:</p>
          @php
              $estado = match($voucher['status']) {
                  'approved' => 'Aprobado',
                  'rejected' => 'Rechazado',
                  'pending' => 'Pendiente',
                  default => ucfirst($voucher['status']),
              };
          @endphp
          <p><strong>Estado:</strong> {{ $estado }}</p>

          @if (!empty($voucher['review_observation']))
              <p class="mt-2 text-sm text-red-700">
                  <strong>Observación del revisor:</strong>
                  {{ $voucher['review_observation'] }}
              </p>
          @endif

          <p class="mt-2">
              @if (str_starts_with($voucher['file_mime'], 'application/pdf'))
                  <iframe src="data:{{ $voucher['file_mime'] }};base64,{{ $voucher['file_base64'] }}"
                          class="w-full h-[500px] border rounded mb-4"></iframe>
              @endif
              <a href="data:{{ $voucher['file_mime'] }};base64,{{ $voucher['file_base64'] }}"
                  download="{{ $voucher['file_name'] }}"
                  class="text-blue-600 underline">
                  Descargar archivo adjunto ({{ $voucher['file_name'] }})
              </a>
          </p>
        </div>
      @endforeach
    @endif

    {{-- 📝 Formulario para subir nuevo comprobante --}}
    @if ($installment['status'] !== 'paid')
      <form method="POST" action="{{ route('portal.voucher.upload') }}" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="installment_id" value="{{ $installment['id'] }}">

        <div class="mb-4">
          <label for="file" class="block text-sm font-semibold mb-1">Archivo del comprobante (PDF o imagen)</label>
          <input type="file" name="file" id="file" accept=".pdf,image/*" required class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
          <label for="observation" class="block text-sm font-semibold mb-1">Observación (opcional)</label>
          <textarea name="observation" id="observation" rows="3" class="w-full border rounded p-2"></textarea>
        </div>

        <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">
          Enviar comprobante
        </button>
      </form>
    @else
      <div class="mt-6 p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded text-sm">
        Esta cuota ya se encuentra <strong>pagada</strong>. No es posible cargar nuevos comprobantes.
      </div>
    @endif

  </div>
</div>
<div class="mt-6">
  <a href="{{ route('portal.dashboard') }}" class="inline-block bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
    Volver
  </a>
</div>
@endsection