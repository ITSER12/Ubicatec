<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Models\Usuario;
use App\Mail\CodigoAcceso;


class RegistroController extends Controller
{
    // ════════════════════════════════════════════
    // PASO 1 — Validar datos y enviar código
    // ════════════════════════════════════════════
    public function store(Request $request)
    {
        $request->validate([
            'no_control'  => 'required|string|unique:usuarios,no_control',
            'nombre'      => 'required|string|max:100',
            'ape_paterno' => 'nullable|string|max:100',
            'ape_materno' => 'nullable|string|max:100',
            'correo'      => 'required|email|unique:usuarios,correo',
            'password'    => 'required|min:6',
            'carrera' => 'required|in:A,C,E,G,I,S,T',
        ], [
            'no_control.unique' => 'Ese número de control ya está registrado.',
            'correo.unique'     => 'Ese correo ya está en uso.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
            'carrera.required'  => 'Selecciona tu carrera.',
            'carrera.exists'    => 'La carrera seleccionada no es válida.',
        ]);

        $codigo = rand(100000, 999999);

        Session::put('registro_datos', $request->only([
            'no_control','nombre','ape_paterno','ape_materno','correo','password','carrera'
        ]));
        Session::put('registro_codigo', $codigo);
        Session::put('registro_correo', $request->correo);
        Session::put('registro_expira', now()->addMinutes(5));
        Session::put('registro_step',   'registro');

        try {
            Mail::to($request->correo)
                ->send(new CodigoAcceso($codigo, $request->nombre));
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'No se pudo enviar el correo. Verifica la dirección e intenta de nuevo.');
        }

        return back();
    }

    // ════════════════════════════════════════════
    // PASO 2 — Verificar código y crear cuenta
    // ════════════════════════════════════════════
    public function verificar(Request $request)
    {
        $request->validate([
            'codigo' => 'required|digits:6',
        ], [
            'codigo.required' => 'Ingresa el código de verificación.',
            'codigo.digits'   => 'El código debe tener 6 dígitos.',
        ]);

        if (!Session::has('registro_expira') || now()->gt(Session::get('registro_expira'))) {
            Session::forget(['registro_datos','registro_codigo','registro_correo','registro_expira','registro_step']);
            return redirect()->route('login')
                ->with('error', 'El código expiró. Vuelve a registrarte.');
        }

        if ((string) $request->codigo !== (string) Session::get('registro_codigo')) {
            return back()->with('error', 'Código incorrecto. Intenta de nuevo.');
        }

        $datos = Session::get('registro_datos');

        // Doble verificación por si ya existe
        if (DB::table('usuarios')->where('no_control', $datos['no_control'])->exists()) {
            Session::forget(['registro_datos','registro_codigo','registro_correo','registro_expira','registro_step']);
            return redirect()->route('login')->with('error', 'Ese número de control ya fue registrado.');
        }

        if (DB::table('usuarios')->where('correo', $datos['correo'])->exists()) {
            Session::forget(['registro_datos','registro_codigo','registro_correo','registro_expira','registro_step']);
            return redirect()->route('login')->with('error', 'Ese correo ya fue registrado.');
        }

        Usuario::create([
            'no_control'  => $datos['no_control'],
            'nombre'      => $datos['nombre'],
            'ape_paterno' => $datos['ape_paterno'] ?? null,
            'ape_materno' => $datos['ape_materno'] ?? null,
            'correo'      => $datos['correo'],
            'password'    => bcrypt($datos['password']),
            'carrera'     => $datos['carrera'],
            'rol'         => 'estudiante',
        ]);

        Session::forget(['registro_datos','registro_codigo','registro_correo','registro_expira','registro_step']);

        return redirect()->route('login')
            ->with('success', '¡Cuenta creada exitosamente! Ya puedes iniciar sesión.');
    }

    // ════════════════════════════════════════════
    // REENVIAR código
    // ════════════════════════════════════════════
    public function reenviar()
    {
        $datos = Session::get('registro_datos');

        if (!$datos) {
            return redirect()->route('login')
                ->with('error', 'Sesión expirada. Vuelve a registrarte.');
        }

        $codigo = rand(100000, 999999);
        Session::put('registro_codigo', $codigo);
        Session::put('registro_expira', now()->addMinutes(5));

        try {
            Mail::to($datos['correo'])
                ->send(new CodigoAcceso($codigo, $datos['nombre']));
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo reenviar el correo.');
        }

        return back()->with('success', '📧 Código reenviado a ' . $datos['correo']);
    }
}