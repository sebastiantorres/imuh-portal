<!-- resources/views/portal/documents/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
  <h1 class="text-3xl font-extrabold mb-6">Documentos Pendientes</h1>

  @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
      {{ session('success') }}
    </div>
  @endif

  @if($requirements->isEmpty())
    <p class="text-gray-600">No tienes documentos pendientes.</p>
  @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      @foreach($requirements as $req)
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold mb-2">{{ $req['name'] }}</h2>
          @if(!empty($req['description']))
            <p class="text-gray-600 mb-4">{{ $req['description'] }}</p>
          @endif

          @if(!empty($req['file_url']))
            <p class="mb-4">
              <a href="{{ $req['file_url'] }}" target="_blank"
                 class="text-indigo-600 hover:underline">Ver archivo cargado</a>
            </p>
          @endif

          <form action="{{ route('portal.documents.upload', $req['id']) }}" 
                method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
              <label for="file-{{ $req['id'] }}" class="block text-sm font-medium text-gray-700">
                Seleccionar archivo (máx. 5 MB)
              </label>
              <input type="file" name="file" id="file-{{ $req['id'] }}" required
                     class="mt-1 block w-full text-sm text-gray-700 file:bg-gray-100 file:border file:border-gray-300 file:rounded file:py-2 file:px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                     data-max-bytes="5242880">
              @error('file')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit"
                    class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded">
              Subir documento
            </button>
          </form>
        </div>
      @endforeach
    </div>
  @endif

  <div class="mt-8">
    <a href="{{ route('portal.dashboard') }}"
       class="inline-block text-indigo-500 hover:underline">← Volver al Dashboard</a>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const maxBytes = parseInt(document.querySelector('input[data-max-bytes]')?.dataset.maxBytes || 0, 10);
    document.querySelectorAll('input[type="file"]').forEach(input => {
      input.addEventListener('change', function() {
        const file = this.files[0];
        if (file && maxBytes && file.size > maxBytes) {
          alert('El archivo supera el tamaño máximo de 5 MB.');
          this.value = '';
        }
      });
    });
  });
</script>
@endpush
