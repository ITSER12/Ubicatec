@extends('layouts.app')

@section('title','Usuarios')
@section('content')
<div class="container">
    <h1 class="mb-4">Gestión de Usuarios</h1>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <a href="{{ route('cpanel.usuarios.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Nuevo Usuario
    </a>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No. Control</th>
                    <th>Nombre Completo</th>
                    <th>Correo Electrónico</th>
                    <th>Código Carrera</th>
                    <th width="150px">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->no_control }}</td>
                    <td>{{ $usuario->nombre }} {{ $usuario->ape_paterno }} {{ $usuario->ape_materno }}</td>
                    <td>{{ $usuario->correo ?? 'No registrado' }}</td>
                    <td>{{ $usuario->cod_carrera }}</td>
                    <td>
                        <a href="{{ route('cpanel.usuarios.edit', $usuario->no_control) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <form action="{{ route('cpanel.usuarios.destroy', $usuario->no_control) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No hay usuarios registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection