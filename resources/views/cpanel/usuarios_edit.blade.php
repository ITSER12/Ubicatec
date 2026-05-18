@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Usuario</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('cpanel.usuarios.update', $usuario->no_control) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. Control</label>
                        <input type="text" class="form-control" value="{{ $usuario->no_control }}" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre *</label>
                        <input type="text" name="nombre" id="nombre" 
                               class="form-control @error('nombre') is-invalid @enderror" 
                               value="{{ old('nombre', $usuario->nombre) }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="ape_paterno" class="form-label">Apellido Paterno *</label>
                        <input type="text" name="ape_paterno" id="ape_paterno" 
                               class="form-control @error('ape_paterno') is-invalid @enderror" 
                               value="{{ old('ape_paterno', $usuario->ape_paterno) }}" required>
                        @error('ape_paterno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="ape_materno" class="form-label">Apellido Materno</label>
                        <input type="text" name="ape_materno" id="ape_materno" 
                               class="form-control" value="{{ old('ape_materno', $usuario->ape_materno) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="correo" class="form-label">Correo Electrónico *</label>
                        <input type="email" name="correo" id="correo" 
                               class="form-control @error('correo') is-invalid @enderror" 
                               value="{{ old('correo', $usuario->correo) }}" required>
                        @error('correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="cod_carrera" class="form-label">Código Carrera *</label>
                        <input type="text" name="cod_carrera" id="cod_carrera" 
                               class="form-control @error('cod_carrera') is-invalid @enderror" 
                               value="{{ old('cod_carrera', $usuario->cod_carrera) }}" required>
                        @error('cod_carrera')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input type="password" name="password" id="password" 
                               class="form-control @error('password') is-invalid @enderror">
                        <small class="text-muted">Dejar en blanco para mantener la contraseña actual</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="form-control">
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('cpanel.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection