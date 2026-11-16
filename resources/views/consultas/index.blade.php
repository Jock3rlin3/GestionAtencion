@extends('layouts.app')

@section('title', 'Consultas de atencion')

@section('content')
    <div class="page-heading d-flex flex-wrap justify-content-between align-items-end gap-3">
        <div>
            <h1 class="mb-2">Consultas de atencion</h1>
        </div>
        <a href="{{ route('consultas.create') }}" class="btn btn-primary px-3">+ Nueva consulta</a>
    </div>

    <section class="card overflow-hidden">
        <div class="card-header p-3 p-md-4">
            <form method="GET" action="{{ route('consultas.index') }}" class="row g-2 align-items-center">
                <div class="col-md">
                    <label class="visually-hidden" for="buscar">Buscar consultas</label>
                    <input id="buscar" name="buscar" value="{{ $buscar }}" class="form-control" placeholder="Buscar por paciente, motivo o tratamiento">
                </div>
                <input type="hidden" name="orden" value="{{ $orden }}">
                <input type="hidden" name="direccion" value="{{ $direccion }}">
                <div class="col-auto"><button class="btn btn-outline-primary px-3" type="submit">Buscar</button></div>
                @if ($buscar !== '')
                    <div class="col-auto"><a class="btn btn-light" href="{{ route('consultas.index') }}">Limpiar</a></div>
                @endif
                <div class="col-12 small text-secondary mt-2">
                    {{ $consultas->total() }} {{ $consultas->total() === 1 ? 'consulta' : 'consultas' }}
                </div>
            </form>
        </div>

        @if ($consultas->isEmpty())
            <div class="empty-state">
                <p class="text-secondary mb-3">{{ $buscar ? 'No se encontraron consultas.' : 'No hay consultas registradas.' }}</p>
                @unless ($buscar)
                    <a href="{{ route('consultas.create') }}" class="btn btn-primary">Crear consulta</a>
                @endunless
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            @foreach (['paciente' => 'Paciente', 'fecha_atencion' => 'Fecha y hora', 'estado' => 'Estado'] as $columna => $etiqueta)
                                @php($siguienteDireccion = $orden === $columna && $direccion === 'asc' ? 'desc' : 'asc')
                                <th scope="col">
                                    <a class="sort-link" href="{{ route('consultas.index', array_merge(request()->query(), ['orden' => $columna, 'direccion' => $siguienteDireccion])) }}">
                                        {{ $etiqueta }}@if ($orden === $columna) <span aria-hidden="true">{{ $direccion === 'asc' ? '↑' : '↓' }}</span>@endif
                                    </a>
                                </th>
                            @endforeach
                            <th scope="col">Tratamiento</th>
                            <th scope="col">Motivo</th>
                            <th scope="col" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($consultas as $consulta)
                            <tr>
                                <td class="fw-semibold">{{ $consulta->paciente }}</td>
                                <td class="text-nowrap">{{ $consulta->fecha_atencion->format('d/m/Y H:i') }}</td>
                                <td><span class="badge rounded-pill badge-{{ $consulta->estado }}">{{ ucfirst($consulta->estado) }}</span></td>
                                <td>{{ $consulta->tratamiento->nombre }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($consulta->motivo, 48) }}</td>
                                <td>
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <a href="{{ route('consultas.edit', $consulta) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                        <form action="{{ route('consultas.destroy', $consulta) }}" method="POST" onsubmit="return confirm('¿Eliminar esta consulta de atencion?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($consultas->hasPages())
                <div class="card-footer px-3 px-md-4 py-3">{{ $consultas->links() }}</div>
            @endif
        @endif
    </section>
@endsection
