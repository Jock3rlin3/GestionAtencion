@extends('layouts.app')

@section('title', 'Editar tratamiento')

@section('content')
    <div class="page-heading">
        <h1 class="mb-2">Editar tratamiento</h1>
    </div>
    <section class="card p-3 p-md-4">
        @include('tratamientos.form')
    </section>
@endsection
