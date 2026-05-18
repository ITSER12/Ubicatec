@extends('layouts.app')

@section('title', 'Respaldo BD')

@section('content')
<div class="container mt-5">

    <h2 class="mb-4">Respaldo de Base de Datos</h2>

    <div class="card p-4 shadow">
        <p>Haz clic en el botón para generar un respaldo de la base de datos.</p>

        <form action="{{ route('backup.generar') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">
                Generar Respaldo
            </button>
        </form>
    </div>

</div>
@endsection