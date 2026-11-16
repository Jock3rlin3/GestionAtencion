@extends('layouts.app')

@section('title', 'Tratamientos')

@section('content')
    <div class="page-heading d-flex flex-wrap justify-content-between align-items-end gap-3">
        <div>
            <h1 class="mb-2">Tratamientos</h1>
        </div>
        <a href="{{ route('tratamientos.create') }}" class="btn btn-primary px-3">+ Nuevo tratamiento</a>
    </div>

    <section class="card overflow-hidden">
        <div class="card-header p-3 p-md-4">
            <form method="GET" action="{{ route('tratamientos.index') }}" class="row g-2 align-items-center">
                <div class="col-md">
                    <label class="visually-hidden" for="buscar">Buscar tratamientos</label>
                    <input id="buscar" name="buscar" value="{{ $buscar }}" class="form-control" placeholder="Buscar por nombre o descripcion">
                </div>
                <input type="hidden" name="orden" value="{{ $orden }}">
                <input type="hidden" name="direccion" value="{{ $direccion }}">
                <div class="col-auto"><button class="btn btn-outline-primary px-3" type="submit">Buscar</button></div>
                @if ($buscar !== '')
                    <div class="col-auto"><a class="btn btn-light" href="{{ route('tratamientos.index') }}">Limpiar</a></div>
                @endif
                <div class="col-12 small text-secondary mt-2">
                    {{ $tratamientos->total() }} {{ $tratamientos->total() === 1 ? 'tratamiento' : 'tratamientos' }}
                </div>
            </form>
        </div>

        @if ($tratamientos->isEmpty())
            <div class="empty-state">
                <p class="text-secondary mb-3">{{ $buscar ? 'No se encontraron tratamientos.' : 'No hay tratamientos registrados.' }}</p>
                @unless ($buscar)
                    <a href="{{ route('tratamientos.create') }}" class="btn btn-primary">Crear tratamiento</a>
                @endunless
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            @foreach (['nombre' => 'Tratamiento', 'duracion_minutos' => 'Duracion', 'precio' => 'Precio'] as $columna => $etiqueta)
                                @php($siguienteDireccion = $orden === $columna && $direccion === 'asc' ? 'desc' : 'asc')
                                <th scope="col">
                                    <a class="sort-link" href="{{ route('tratamientos.index', array_merge(request()->query(), ['orden' => $columna, 'direccion' => $siguienteDireccion])) }}">
                                        {{ $etiqueta }}@if ($orden === $columna) <span aria-hidden="true">{{ $direccion === 'asc' ? '↑' : '↓' }}</span>@endif
                                    </a>
                                </th>
                            @endforeach
                            <th scope="col">Consultas</th>
                            <th scope="col" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tratamientos as $tratamiento)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $tratamiento->nombre }}</div>
                                    <div class="small text-secondary">{{ \Illuminate\Support\Str::limit($tratamiento->descripcion ?: 'Sin descripcion', 82) }}</div>
                                </td>
                                <td>{{ $tratamiento->duracion_minutos }} min</td>
                                <td class="fw-semibold">${{ number_format((float) $tratamiento->precio, 2, ',', '.') }}</td>
                                <td><span class="badge rounded-pill badge-soft">{{ $tratamiento->consultas_count }}</span></td>
                                <td>
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <a href="{{ route('tratamientos.edit', $tratamiento) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                        @if ($tratamiento->consultas_count === 0)
                                            <form action="{{ route('tratamientos.destroy', $tratamiento) }}" method="POST" onsubmit="return confirm('Eliminar este tratamiento?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-outline-danger" type="button" disabled title="Tiene consultas asociadas">Eliminar</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($tratamientos->hasPages())
                <div class="card-footer px-3 px-md-4 py-3">{{ $tratamientos->links() }}</div>
            @endif
        @endif
    </section>
@endsection
