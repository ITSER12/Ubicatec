<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Mail;
use App\Mail\CodigoAcceso;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $request->validate([
            'no_control' => 'required',
            'password'   => 'required',
        ]);

        $usuario = Usuario::where('no_control', $request->no_control)->first();

        if (!$usuario || $request->password != $usuario->password) {

            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        // GENERAR CODIGO
        $codigo = rand(100000, 999999);

        // GUARDAR CODIGO
        $usuario->codigo_verificacion = $codigo;
        $usuario->save();

        try {

            // ENVIAR CORREO
            Mail::to($usuario->correo)
                ->send(new CodigoAcceso($codigo, $usuario->nombre));

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo enviar el correo',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Código enviado',
            'no_control' => $usuario->no_control,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR CODIGO
    |--------------------------------------------------------------------------
    */

    public function verificarCodigo(Request $request)
    {
        $request->validate([
            'no_control' => 'required',
            'codigo'     => 'required',
        ]);

        $usuario = Usuario::where('no_control', $request->no_control)
            ->where('codigo_verificacion', $request->codigo)
            ->first();

        if (!$usuario) {

            return response()->json([
                'success' => false,
                'message' => 'Código incorrecto'
            ], 401);
        }

        // LIMPIAR CODIGO
        $usuario->codigo_verificacion = null;
        $usuario->save();

        // CREAR TOKEN
        $token = $usuario->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Verificación correcta',

            'token' => $token,

            'usuario' => [
                'no_control' => $usuario->no_control,
                'nombre' => $usuario->nombre,
                'ape_paterno' => $usuario->ape_paterno,
                'ape_materno' => $usuario->ape_materno,
                'correo' => $usuario->correo,
                'rol' => $usuario->rol,
                'foto' => $usuario->foto_url,
            ]
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | ME
    |--------------------------------------------------------------------------
    */

    public function me(Request $request)
    {
        return response()->json($request->user());
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout correcto'
        ]);
    }
}