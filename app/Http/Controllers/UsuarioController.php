<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class UsuarioController extends Controller
{

    public function index()
    {
        return view('usuarios.index');
    }

    public function api()
    {
        return Usuario::all();
    }

    public function editar($id)
    {
        return Usuario::findOrFail($id);
    }

    public function store(Request $request)
{
    $datos = $request->except('password');
    if ($request->filled('password')) {
        $datos['password'] = bcrypt($request->password);
    }
    Usuario::create($datos);
    return response()->json(['ok' => true]);
}

    public function update(Request $request, $id)
{
    $usuario = Usuario::findOrFail($id);

    $datos = $request->except('password'); // todo menos password

    // Solo actualiza password si no está vacío
    if ($request->filled('password')) {
        $datos['password'] = bcrypt($request->password);
    }

    $usuario->update($datos);

    return response()->json(['ok' => true]);
}

    public function destroy($id)
    {

        Usuario::destroy($id);

        return response()->json([
            "ok" => true
        ]);

    }
           public function updateRol(Request $request, $id)
{
    $sesion = session('usuario');
    $rolesAltos = ['administrador', 'jefe_departamento'];

    // Jefe de departamento no puede asignar admin ni jefe
    if (in_array($request->rol, $rolesAltos) && $sesion->rol !== 'administrador') {
        return response()->json(['ok' => false, 'message' => 'No autorizado'], 403);
    }

    $usuario = Usuario::findOrFail($id);
    $usuario->rol = $request->rol;
    $usuario->save();

    return response()->json(['ok' => true, 'rol' => $usuario->rol]);
}
}