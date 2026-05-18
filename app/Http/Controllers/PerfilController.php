<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Usuario;

class PerfilController extends Controller
{
    // Datos del usuario actual
    public function show()
    {
        $user = session('usuario');

        return response()->json([
            'id'          => $user->no_control,
            'name'        => $user->nombre,
            'email'       => $user->correo,
            'descripcion' => $user->descripcion,
            'rol'         => $user->rol,
            'rol_label'   => $user->rol_label,
            'foto_url'    => $user->foto_url,
        ]);
    }

    // Actualizar foto, descripción y rol
    public function update(Request $request)
{
    $userSesion = session('usuario');

    $request->validate([
        'descripcion' => 'nullable|string|max:300',
        'rol'         => 'nullable|in:administrador,docente,jefe_departamento,estudiante', // ← nullable
        'foto'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($request->filled('user_id') && $userSesion->esAdmin()) {
        $target = Usuario::findOrFail($request->user_id);
    } else {
        $target = Usuario::findOrFail($userSesion->no_control);
    }

    // Foto — solo admin O el propio usuario si NO es admin
    if ($request->hasFile('foto')) {
        // ← aquí estaba el bloqueo, ahora cualquiera puede subir su foto
        if ($target->foto) Storage::disk('public')->delete($target->foto);
        $target->foto = $request->file('foto')->store('perfiles', 'public');
    }

    $target->descripcion = $request->descripcion;
    $target->save();

    if ($target->no_control === $userSesion->no_control) {
        session(['usuario' => $target->fresh()]);
    }

    return response()->json([
        'success'   => true,
        'message'   => 'Perfil actualizado correctamente.',
        'foto_url'  => $target->foto_url,
        'rol'       => $target->rol,
        'rol_label' => $target->rol_label,
        'name'      => $target->nombre,
    ]);
}

    // Solo admin puede eliminar
    public function destroy($id)
    {
        $userSesion = session('usuario');
        abort_unless($userSesion->esAdmin(), 403, 'No autorizado.');

        $target = Usuario::findOrFail($id);
        if ($target->foto) Storage::disk('public')->delete($target->foto);
        $target->delete();

        return response()->json(['success' => true, 'message' => 'Usuario eliminado.']);
    }
} 