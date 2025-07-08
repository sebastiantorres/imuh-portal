@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
  <h1 class="text-2xl font-bold mb-4 text-primary">Enviar comprobante de pago sin cuota asignada</h1>

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

  <form method="POST" action="{{ route('portal.voucher.unassigned.upload') }}" enctype="multipart/form-data">
    @csrf

    <div class="mb-4">
      <label for="file" class="block text-sm font-semibold mb-1">Archivo del comprobante</label>
      <input type="file" name="file" id="file" accept=".pdf,image/*" required class="w-full border rounded p-2">
    </div>

    <div class="mb-4">
      <label for="observation" class="block text-sm font-semibold mb-1">Observación (opcional)</label>
      <textarea name="observation" id="observation" rows="3" class="w-full border rounded p-2">{{ old('observation') }}</textarea>
    </div>

    <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">
      Enviar comprobante
    </button>
  </form>

  <div class="mt-6">
    <a href="{{ route('portal.dashboard') }}" class="inline-block bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
      Volver
    </a>
  </div>
</div>
@endsection