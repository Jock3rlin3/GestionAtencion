@extends('layouts.app')

@section('title', 'Nueva consulta')

@section('content')
    <div class="page-heading">
        <h1 class="mb-2">Nueva consulta de atencion</h1>
    </div>
    <section class="card p-3 p-md-4">
        @include('consultas.form')
    </section>
@endsection
