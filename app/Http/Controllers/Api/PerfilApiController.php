<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Usuario;

class PerfilApiController extends Controller
{
    // =========================================
    // OBTENER PERFIL
    // =========================================
    public function show($id)
    {
        $user = Usuario::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'          => $user->no_control,
                'nombre'      => $user->nombre,
                'correo'      => $user->correo,
                'descripcion' => $user->descripcion,
                'rol'         => $user->rol,
                'rol_label'   => $user->rol_label,

                'foto_url' => $user->foto
                    ? asset('storage/' . $user->foto)
                    : null,
            ]
        ]);
    }

    // =========================================
    // ACTUALIZAR PERFIL (DESCRIPCIÓN + FOTO)
    // =========================================
    public function update(Request $request, $id)
    {
        $user = Usuario::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        // =========================
        // VALIDACIÓN
        // =========================
        $request->validate([
            'descripcion' => 'nullable|string|max:300',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // =========================
        // DESCRIPCIÓN
        // =========================
        if ($request->has('descripcion')) {
            $user->descripcion = $request->descripcion;
        }

        // =========================
        // FOTO DE PERFIL
        // =========================
        if ($request->hasFile('foto')) {

            // borrar foto anterior si existe
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }

            // guardar nueva foto
            $ruta = $request->file('foto')->store('perfiles', 'public');
            $user->foto = $ruta;
        }

        // guardar cambios
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Perfil actualizado correctamente',

            'data' => [
                'id'          => $user->no_control,
                'nombre'      => $user->nombre,
                'correo'      => $user->correo,
                'descripcion' => $user->descripcion,
                'rol'         => $user->rol,
                'rol_label'   => $user->rol_label,

                'foto_url' => $user->foto
                    ? asset('storage/' . $user->foto)
                    : null,
            ]
        ]);
    }
}