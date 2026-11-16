@extends('layouts.app')

@section('title', 'Editar consulta')

@section('content')
    <div class="page-heading">
        <h1 class="mb-2">Editar consulta</h1>
    </div>
    <section class="card p-3 p-md-4">
        @include('consultas.form')
    </section>
@endsection
