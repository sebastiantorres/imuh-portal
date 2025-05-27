@extends('layouts.app')

@section('content')
  <h1>Mis Documentos</h1>
  <ul>
    @forelse($documents as $doc)
      <li>
        <a href="{{ $doc['url'] }}" target="_blank">
          {{ $doc['name'] }}
        </a>
      </li>
    @empty
      <li>No tienes documentos cargados.</li>
    @endforelse
  </ul>
  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Cerrar sesión</button>
  </form>
@endsection
